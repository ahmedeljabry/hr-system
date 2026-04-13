<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function __construct(protected NotificationService $notificationService) {}
    /**
     * Get all leave types for a client.
     */
    public function getLeaveTypes(Client $client)
    {
        return LeaveType::where('client_id', $client->id)->orderBy('name')->get();
    }

    /**
     * Get eligible leave types for an employee (filtered by gender).
     */
    public function getEligibleLeaveTypes(Employee $employee)
    {
        return LeaveType::where('client_id', $employee->client_id)
            ->where(function($q) use ($employee) {
                $q->where('gender', 'all')
                  ->orWhere('gender', $employee->gender);
            })
            ->orderBy('name')
            ->get();
    }

    /**
     * Create a leave type for a client.
     */
    public function createLeaveType(Client $client, array $data): LeaveType
    {
        $data['client_id'] = $client->id;
        return LeaveType::create($data);
    }

    /**
     * Update a leave type.
     */
    public function updateLeaveType(LeaveType $leaveType, array $data): LeaveType
    {
        $oldGender = $leaveType->gender;
        $leaveType->update($data);
        $newGender = $leaveType->gender;

        // If gender restriction changed, cleanup ineligible balances
        if ($oldGender !== $newGender && $newGender !== 'all') {
            LeaveBalance::where('leave_type_id', $leaveType->id)
                ->whereHas('employee', function($q) use ($newGender) {
                    $q->where('gender', '!=', $newGender);
                })
                ->delete();
            
            // Also cancel pending requests for ineligible employees
            LeaveRequest::where('leave_type_id', $leaveType->id)
                ->where('status', 'pending')
                ->whereHas('employee', function($q) use ($newGender) {
                    $q->where('gender', '!=', $newGender);
                })
                ->update(['status' => 'rejected', 'reviewer_comment' => 'Automatically rejected: Leave type restricted to another gender.']);
        }

        return $leaveType->fresh();
    }

    /**
     * Delete a leave type.
     */
    public function deleteLeaveType(LeaveType $leaveType): bool
    {
        return $leaveType->delete();
    }

    /**
     * Submit a leave request (by employee).
     */
    public function submitRequest(Employee $employee, array $data): LeaveRequest
    {
        $leaveType = LeaveType::where('client_id', $employee->client_id)
            ->findOrFail($data['leave_type_id']);

        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        // Check gender eligibility
        if ($leaveType->gender !== 'all' && $leaveType->gender !== $employee->gender) {
            throw new \InvalidArgumentException(__('messages.gender_not_eligible_for_leave', ['gender' => __('messages.' . $leaveType->gender)]));
        }

        // Check balance
        $balance = $this->getOrCreateBalance($employee, $leaveType);
        
        $maxDays = $leaveType->max_days_per_year;
        $isAnnual = preg_match('/Annual|سنوي|Annual Leave|إجازة سنوية/i', $leaveType->name);
        if ($isAnnual && $employee->annual_leave_days > 0) {
            $maxDays = $employee->annual_leave_days;
        }

        $remaining = $maxDays - $balance->used_days;

        if ($daysRequested > $remaining && $maxDays > 0) {
            throw new \InvalidArgumentException(__('Insufficient leave balance. You have :remaining days remaining.', ['remaining' => $remaining]));
        }

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'client_id' => $employee->client_id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        // Notify Client
        $this->notificationService->createNotification([
            'client_id' => $employee->client_id,
            'type' => 'leave_request_submitted',
            'title' => 'messages.leave_request_submitted',
            'message' => json_encode(['key' => 'messages.leave_request_submitted_msg', 'params' => ['name' => $employee->name]]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);

        return $leaveRequest;
    }

    /**
     * Approve a leave request (by client).
     */
    public function approve(LeaveRequest $leaveRequest, ?string $comment = null): LeaveRequest
    {
        if (!$leaveRequest->isPending()) {
            throw new \InvalidArgumentException(__('This request has already been reviewed.'));
        }

        return DB::transaction(function () use ($leaveRequest, $comment) {
            $leaveRequest->update([
                'status' => 'approved',
                'reviewer_comment' => $comment,
                'reviewed_at' => now(),
            ]);

            // Deduct from balance
            $balance = $this->getOrCreateBalance(
                $leaveRequest->employee,
                $leaveRequest->leaveType
            );
            $balance->increment('used_days', $leaveRequest->days_count);

            $leaveRequest->fresh();

            // Notify Employee
            $this->notificationService->createNotification([
                'employee_id' => $leaveRequest->employee_id,
                'type' => 'leave_request_approved',
                'title' => 'messages.leave_request_approved',
                'message' => json_encode([
                    'key' => 'messages.leave_request_approved_msg',
                    'params' => [
                        'start' => $leaveRequest->start_date->format('d M'),
                        'end' => $leaveRequest->end_date->format('d M'),
                    ]
                ]),
                'related_type' => LeaveRequest::class,
                'related_id' => $leaveRequest->id,
            ]);

            return $leaveRequest->fresh();
        });
    }

    /**
     * Reject a leave request (by client).
     */
    public function reject(LeaveRequest $leaveRequest, ?string $comment = null): LeaveRequest
    {
        if (!$leaveRequest->isPending()) {
            throw new \InvalidArgumentException(__('This request has already been reviewed.'));
        }

        $leaveRequest->update([
            'status' => 'rejected',
            'reviewer_comment' => $comment,
            'reviewed_at' => now(),
        ]);

        $leaveRequest->fresh();

        // Notify Employee
        $this->notificationService->createNotification([
            'employee_id' => $leaveRequest->employee_id,
            'type' => 'leave_request_rejected',
            'title' => 'messages.leave_request_rejected',
            'message' => json_encode([
                'key' => 'messages.leave_request_rejected_msg',
                'params' => [
                    'start' => $leaveRequest->start_date->format('d M'),
                    'end' => $leaveRequest->end_date->format('d M'),
                ]
            ]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);

        return $leaveRequest->fresh();
    }

    /**
     * Get the employee's current approved leave that still requires return confirmation.
     */
    public function getCurrentLeave(Employee $employee): ?LeaveRequest
    {
        return LeaveRequest::where('employee_id', $employee->id)
            ->with('leaveType')
            ->approved()
            ->awaitingResumption()
            ->started()
            ->orderByDesc('start_date')
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Record the employee return from leave using the current timestamp.
     */
    public function recordReturnToWork(Employee $employee, LeaveRequest $leaveRequest): LeaveRequest
    {
        if ($leaveRequest->employee_id !== $employee->id) {
            throw new \InvalidArgumentException(__('messages.unauthorized'));
        }

        $recordedAt = now();

        if (! $leaveRequest->canEmployeeRecordResumption($recordedAt)) {
            throw new \InvalidArgumentException(__('messages.leave_return_invalid_state'));
        }

        return DB::transaction(function () use ($leaveRequest, $employee, $recordedAt) {
            $leaveRequest->update([
                'resumed_at' => $recordedAt,
                'resumption_recorded_at' => $leaveRequest->resumption_recorded_at ?? $recordedAt,
            ]);

            $this->notificationService->createNotification([
                'client_id' => $leaveRequest->client_id,
                'type' => 'leave_return_recorded',
                'title' => 'messages.leave_return_recorded',
                'message' => json_encode([
                    'key' => 'messages.leave_return_recorded_msg',
                    'params' => [
                        'name' => $employee->name,
                        'date' => $recordedAt->format('d M Y h:i A'),
                    ],
                ]),
                'related_type' => LeaveRequest::class,
                'related_id' => $leaveRequest->id,
            ]);

            return $leaveRequest->fresh(['employee', 'leaveType']);
        });
    }

    /**
     * Allow the client to adjust or set the return timestamp.
     */
    public function updateReturnToWork(LeaveRequest $leaveRequest, string $resumedAt): LeaveRequest
    {
        if (! $leaveRequest->canClientManageResumption()) {
            throw new \InvalidArgumentException(__('messages.leave_return_invalid_state'));
        }

        $returnAt = Carbon::parse($resumedAt);
        $leaveStart = $leaveRequest->start_date->copy()->startOfDay();

        if ($returnAt->lt($leaveStart) || $returnAt->gt(now())) {
            throw new \InvalidArgumentException(__('messages.leave_return_invalid_date'));
        }

        return DB::transaction(function () use ($leaveRequest, $returnAt) {
            $originalReturnAt = $leaveRequest->resumed_at?->copy();
            $recordedAt = $leaveRequest->resumption_recorded_at ?? now();

            $leaveRequest->update([
                'resumed_at' => $returnAt,
                'resumption_recorded_at' => $recordedAt,
            ]);

            if (! $originalReturnAt || ! $originalReturnAt->equalTo($returnAt)) {
                $this->notificationService->createNotification([
                    'employee_id' => $leaveRequest->employee_id,
                    'type' => 'leave_return_updated',
                    'title' => 'messages.leave_return_updated',
                    'message' => json_encode([
                        'key' => 'messages.leave_return_updated_msg',
                        'params' => [
                            'date' => $returnAt->format('d M Y h:i A'),
                        ],
                    ]),
                    'related_type' => LeaveRequest::class,
                    'related_id' => $leaveRequest->id,
                ]);
            }

            return $leaveRequest->fresh(['employee', 'leaveType']);
        });
    }

    /**
     * Get leave balance summary for an employee.
     */
    public function getBalanceSummary(Employee $employee): array
    {
        $leaveTypes = LeaveType::where('client_id', $employee->client_id)
            ->where(function($q) use ($employee) {
                $q->where('gender', 'all')
                  ->orWhere('gender', $employee->gender);
            })
            ->get();
        $year = now()->year;

        $summary = [];
        foreach ($leaveTypes as $type) {
            $maxDays = $type->max_days_per_year;
            
            // Override with employee-specific annual leave days if this is the annual leave type
            $isAnnual = preg_match('/Annual|سنوي|Annual Leave|إجازة سنوية/i', $type->name);
            if ($isAnnual && $employee->annual_leave_days > 0) {
                $maxDays = $employee->annual_leave_days;
            }

            $balance = LeaveBalance::firstOrNew([
                'employee_id' => $employee->id,
                'leave_type_id' => $type->id,
                'year' => $year,
            ], ['used_days' => 0]);

            $summary[] = [
                'type' => $type,
                'max_days' => $maxDays,
                'used_days' => (float) $balance->used_days,
                'remaining' => max(0, $maxDays - $balance->used_days),
            ];
        }

        return $summary;
    }

    /**
     * Get leave requests for an employee.
     */
    public function getEmployeeRequests(Employee $employee)
    {
        return LeaveRequest::where('employee_id', $employee->id)
            ->with('leaveType')
            ->latest()
            ->paginate(15);
    }

    /**
     * Get pending leave requests for a client.
     */
    public function getPendingRequests(Client $client)
    {
        return LeaveRequest::where('client_id', $client->id)
            ->where('status', 'pending')
            ->with(['employee', 'leaveType'])
            ->latest()
            ->paginate(15);
    }

    /**
     * Get all leave requests for a client.
     */
    public function getAllRequests(Client $client, ?string $status = null)
    {
        $query = LeaveRequest::where('client_id', $client->id)
            ->with(['employee', 'leaveType'])
            ->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        return $query->paginate(15);
    }

    /**
     * Get or create a leave balance for the current year.
     */
    private function getOrCreateBalance(Employee $employee, LeaveType $leaveType): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => now()->year,
            ],
            ['used_days' => 0]
        );
    }
}
