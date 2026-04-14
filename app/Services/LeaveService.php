<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Employee;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveRequestAction;
use App\Models\LeaveType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    private const REVIEWABLE_STATUSES = ['pending', 'postponed'];

    private const FILTERABLE_STATUSES = ['pending', 'approved', 'rejected', 'postponed'];

    private const EMPLOYEE_SORTS = ['created_at', 'start_date', 'end_date', 'status'];

    private const CLIENT_SORTS = ['created_at', 'start_date', 'end_date', 'status', 'reviewed_at'];

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
            ->where(function (Builder $query) use ($employee) {
                $query->where('gender', 'all')
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

        if ($oldGender !== $newGender && $newGender !== 'all') {
            LeaveBalance::where('leave_type_id', $leaveType->id)
                ->whereHas('employee', function (Builder $query) use ($newGender) {
                    $query->where('gender', '!=', $newGender);
                })
                ->delete();

            LeaveRequest::where('leave_type_id', $leaveType->id)
                ->where('status', 'pending')
                ->whereHas('employee', function (Builder $query) use ($newGender) {
                    $query->where('gender', '!=', $newGender);
                })
                ->update([
                    'status' => 'rejected',
                    'reviewer_comment' => 'Automatically rejected: Leave type restricted to another gender.',
                ]);
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
    public function submitRequest(Employee $employee, array $data, ?User $actor = null): LeaveRequest
    {
        $leaveType = LeaveType::where('client_id', $employee->client_id)
            ->findOrFail($data['leave_type_id']);

        $startDate = Carbon::parse($data['start_date'])->startOfDay();
        $endDate = Carbon::parse($data['end_date'])->startOfDay();
        $daysRequested = $startDate->diffInDays($endDate) + 1;

        $this->assertLeaveEligibility($employee, $leaveType);
        $this->assertLeaveBalanceAvailable($employee, $leaveType, $startDate, $daysRequested);

        $leaveRequest = LeaveRequest::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $leaveType->id,
            'client_id' => $employee->client_id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'reason' => $data['reason'] ?? null,
            'status' => 'pending',
        ]);

        $this->logAction(
            $leaveRequest,
            'submitted',
            $actor,
            null,
            null,
            $this->snapshot($leaveRequest)
        );

        $this->notificationService->createNotification([
            'client_id' => $employee->client_id,
            'type' => 'leave_request_submitted',
            'title' => 'messages.leave_request_submitted',
            'message' => json_encode([
                'key' => 'messages.leave_request_submitted_msg',
                'params' => ['name' => $employee->name],
            ]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);

        return $leaveRequest->fresh(['leaveType', 'latestAction']);
    }

    /**
     * Approve a leave request (by client).
     */
    public function approve(LeaveRequest $leaveRequest, ?string $comment = null, ?User $actor = null): LeaveRequest
    {
        if (!in_array($leaveRequest->status, self::REVIEWABLE_STATUSES, true)) {
            throw new \InvalidArgumentException(__('messages.leave_request_reviewed_already'));
        }

        return DB::transaction(function () use ($leaveRequest, $comment, $actor) {
            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);
            $oldValues = $this->snapshot($leaveRequest);

            $this->assertLeaveBalanceAvailable(
                $leaveRequest->employee,
                $leaveRequest->leaveType,
                $leaveRequest->start_date->copy()->startOfDay(),
                $leaveRequest->days_count
            );

            $leaveRequest->update([
                'status' => 'approved',
                'reviewer_comment' => $comment,
                'reviewed_at' => now(),
            ]);

            $this->adjustBalanceUsage(
                $leaveRequest->employee,
                $leaveRequest->leaveType,
                $leaveRequest->start_date->year,
                $leaveRequest->days_count
            );

            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);

            $this->logAction(
                $leaveRequest,
                'approved',
                $actor,
                $comment,
                $oldValues,
                $this->snapshot($leaveRequest)
            );

            $this->sendEmployeeDecisionNotification($leaveRequest, 'approved');

            return $leaveRequest->fresh(['leaveType', 'latestAction']);
        });
    }

    /**
     * Reject a leave request (by client).
     */
    public function reject(LeaveRequest $leaveRequest, ?string $comment = null, ?User $actor = null): LeaveRequest
    {
        if (!in_array($leaveRequest->status, self::REVIEWABLE_STATUSES, true)) {
            throw new \InvalidArgumentException(__('messages.leave_request_reviewed_already'));
        }

        return DB::transaction(function () use ($leaveRequest, $comment, $actor) {
            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);
            $oldValues = $this->snapshot($leaveRequest);

            $leaveRequest->update([
                'status' => 'rejected',
                'reviewer_comment' => $comment,
                'reviewed_at' => now(),
            ]);

            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);

            $this->logAction(
                $leaveRequest,
                'rejected',
                $actor,
                $comment,
                $oldValues,
                $this->snapshot($leaveRequest)
            );

            $this->sendEmployeeDecisionNotification($leaveRequest, 'rejected');

            return $leaveRequest->fresh(['leaveType', 'latestAction']);
        });
    }

    /**
     * Postpone a leave request (by client).
     */
    public function postpone(LeaveRequest $leaveRequest, ?string $comment = null, ?User $actor = null): LeaveRequest
    {
        if ($leaveRequest->isRejected()) {
            throw new \InvalidArgumentException(__('messages.leave_request_cannot_be_postponed'));
        }

        return DB::transaction(function () use ($leaveRequest, $comment, $actor) {
            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);
            $oldValues = $this->snapshot($leaveRequest);

            if ($leaveRequest->isApproved()) {
                $this->adjustBalanceUsage(
                    $leaveRequest->employee,
                    $leaveRequest->leaveType,
                    $leaveRequest->start_date->year,
                    -$leaveRequest->days_count
                );
            }

            $leaveRequest->update([
                'status' => 'postponed',
                'reviewer_comment' => $comment,
                'reviewed_at' => now(),
            ]);

            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);

            $this->logAction(
                $leaveRequest,
                'postponed',
                $actor,
                $comment,
                $oldValues,
                $this->snapshot($leaveRequest)
            );

            $this->sendEmployeePostponedNotification($leaveRequest);

            return $leaveRequest->fresh(['leaveType', 'latestAction']);
        });
    }

    /**
     * Update a leave request from the company side.
     */
    public function updateByCompany(LeaveRequest $leaveRequest, array $data, User $actor): LeaveRequest
    {
        return DB::transaction(function () use ($leaveRequest, $data, $actor) {
            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);
            $oldValues = $this->snapshot($leaveRequest);

            $leaveType = isset($data['leave_type_id'])
                ? LeaveType::where('client_id', $leaveRequest->client_id)->findOrFail($data['leave_type_id'])
                : $leaveRequest->leaveType;

            $startDate = isset($data['start_date'])
                ? Carbon::parse($data['start_date'])->startOfDay()
                : $leaveRequest->start_date->copy()->startOfDay();

            $endDate = isset($data['end_date'])
                ? Carbon::parse($data['end_date'])->startOfDay()
                : $leaveRequest->end_date->copy()->startOfDay();

            $newStatus = $this->resolveStatusAction($leaveRequest->status, $data['status_action'] ?? 'keep');
            $requestedDays = $startDate->diffInDays($endDate) + 1;

            $this->assertLeaveEligibility($leaveRequest->employee, $leaveType);

            if ($newStatus === 'approved') {
                $credit = null;

                if ($leaveRequest->isApproved()) {
                    $credit = [
                        'leave_type_id' => $leaveRequest->leave_type_id,
                        'year' => $leaveRequest->start_date->year,
                        'days' => $leaveRequest->days_count,
                    ];
                }

                $this->assertLeaveBalanceAvailable(
                    $leaveRequest->employee,
                    $leaveType,
                    $startDate,
                    $requestedDays,
                    $credit
                );
            }

            $leaveRequest->fill([
                'leave_type_id' => $leaveType->id,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'reason' => $data['reason'] ?? $leaveRequest->reason,
                'status' => $newStatus,
            ]);

            if (array_key_exists('reviewer_comment', $data)) {
                $leaveRequest->reviewer_comment = $data['reviewer_comment'];
            }

            if (($data['status_action'] ?? 'keep') !== 'keep') {
                $leaveRequest->reviewed_at = now();
            }

            if (!empty($data['clear_resumption'])) {
                $leaveRequest->resumption_at = null;
                $leaveRequest->resumption_notes = null;
            } else {
                if (array_key_exists('resumption_at', $data) && !empty($data['resumption_at'])) {
                    $leaveRequest->resumption_at = Carbon::parse($data['resumption_at']);
                }

                if (array_key_exists('resumption_notes', $data)) {
                    $leaveRequest->resumption_notes = $data['resumption_notes'];
                }
            }

            $leaveRequest->save();

            $this->syncApprovedBalanceAfterUpdate($leaveRequest, $oldValues);

            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);

            $action = $this->determineUpdateAction($oldValues, $leaveRequest, $data['status_action'] ?? 'keep');
            $note = $data['reviewer_comment'] ?? ($data['resumption_notes'] ?? null);

            $this->logAction(
                $leaveRequest,
                $action,
                $actor,
                $note,
                $oldValues,
                $this->snapshot($leaveRequest)
            );

            $this->sendEmployeeUpdateNotification($leaveRequest, $action, $oldValues);

            return $leaveRequest->fresh(['leaveType', 'latestAction', 'actions.user', 'actions.employee']);
        });
    }

    /**
     * Record employee resumption after leave.
     */
    public function recordEmployeeResumption(LeaveRequest $leaveRequest, Employee $employee, User $actor, ?string $notes = null): LeaveRequest
    {
        if ($leaveRequest->employee_id !== $employee->id) {
            throw new \InvalidArgumentException(__('messages.unauthorized'));
        }

        if (!$leaveRequest->canEmployeeRecordResumption()) {
            throw new \InvalidArgumentException(__('messages.leave_resumption_not_available'));
        }

        return DB::transaction(function () use ($leaveRequest, $actor, $notes) {
            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);
            $oldValues = $this->snapshot($leaveRequest);

            $leaveRequest->update([
                'resumption_at' => now(),
                'resumption_notes' => $notes,
            ]);

            $leaveRequest = $leaveRequest->fresh(['employee', 'leaveType']);

            $this->logAction(
                $leaveRequest,
                'resumed_by_employee',
                $actor,
                $notes,
                $oldValues,
                $this->snapshot($leaveRequest)
            );

            $this->notificationService->createNotification([
                'client_id' => $leaveRequest->client_id,
                'type' => 'leave_resumption_recorded',
                'title' => 'messages.leave_resumption_recorded',
                'message' => json_encode([
                    'key' => 'messages.leave_resumption_recorded_msg',
                    'params' => [
                        'name' => $leaveRequest->employee->name,
                        'date' => $leaveRequest->resumption_at->format('d M Y H:i'),
                    ],
                ]),
                'related_type' => LeaveRequest::class,
                'related_id' => $leaveRequest->id,
            ]);

            return $leaveRequest->fresh(['leaveType', 'latestAction']);
        });
    }

    /**
     * Get leave balance summary for an employee.
     */
    public function getBalanceSummary(Employee $employee): array
    {
        $leaveTypes = LeaveType::where('client_id', $employee->client_id)
            ->where(function (Builder $query) use ($employee) {
                $query->where('gender', 'all')
                    ->orWhere('gender', $employee->gender);
            })
            ->get();

        $year = now()->year;
        $summary = [];

        foreach ($leaveTypes as $type) {
            $maxDays = $this->resolveAllowedDays($employee, $type);

            $balance = LeaveBalance::firstOrNew(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $type->id,
                    'year' => $year,
                ],
                ['used_days' => 0]
            );

            $limit = $balance->total_days ?? $maxDays;

            $summary[] = [
                'type' => $type,
                'max_days' => $limit,
                'used_days' => (float) $balance->used_days,
                'remaining' => max(0, $limit - $balance->used_days),
            ];
        }

        return $summary;
    }

    /**
     * Get leave requests for an employee.
     */
    public function getEmployeeRequests(Employee $employee, array $filters = []): LengthAwarePaginator
    {
        $sort = in_array($filters['sort'] ?? 'created_at', self::EMPLOYEE_SORTS, true)
            ? $filters['sort']
            : 'created_at';

        $direction = strtolower($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query = LeaveRequest::where('employee_id', $employee->id)
            ->with(['leaveType', 'latestAction'])
            ->orderBy($sort, $direction)
            ->orderBy('id', 'desc');

        if (!empty($filters['status']) && in_array($filters['status'], self::FILTERABLE_STATUSES, true)) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Get all leave requests for a client.
     */
    public function getAllRequests(Client $client, array $filters = []): LengthAwarePaginator
    {
        $sort = in_array($filters['sort'] ?? 'created_at', self::CLIENT_SORTS, true)
            ? $filters['sort']
            : 'created_at';

        $direction = strtolower($filters['direction'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $query = LeaveRequest::where('client_id', $client->id)
            ->with(['employee', 'leaveType', 'latestAction'])
            ->orderBy($sort, $direction)
            ->orderBy('id', 'desc');

        if (!empty($filters['status']) && in_array($filters['status'], self::FILTERABLE_STATUSES, true)) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->whereHas('employee', function (Builder $employeeQuery) use ($search) {
                $employeeQuery
                    ->where('name_ar', 'like', '%' . $search . '%')
                    ->orWhere('name_en', 'like', '%' . $search . '%')
                    ->orWhere('position', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['needs_resumption'])) {
            $query->where('status', 'approved')
                ->whereNull('resumption_at')
                ->whereDate('end_date', '<', now()->toDateString());
        }

        return $query->paginate(15)->withQueryString();
    }

    /**
     * Get the active approved leave for an employee.
     */
    public function getCurrentActiveLeave(Employee $employee): ?LeaveRequest
    {
        return LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereNull('resumption_at')
            ->whereDate('start_date', '<=', now()->toDateString())
            ->whereDate('end_date', '>=', now()->toDateString())
            ->with('leaveType')
            ->orderBy('start_date')
            ->first();
    }

    /**
     * Get the approved leave waiting for resumption confirmation.
     */
    public function getPendingResumptionLeave(Employee $employee): ?LeaveRequest
    {
        return LeaveRequest::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->whereNull('resumption_at')
            ->whereDate('end_date', '<', now()->toDateString())
            ->with('leaveType')
            ->orderByDesc('end_date')
            ->first();
    }

    /**
     * Get pending leave requests for a client.
     */
    public function getPendingRequests(Client $client): LengthAwarePaginator
    {
        return LeaveRequest::where('client_id', $client->id)
            ->whereIn('status', self::REVIEWABLE_STATUSES)
            ->with(['employee', 'leaveType'])
            ->latest()
            ->paginate(15);
    }

    /**
     * Get or create a leave balance for a specific year.
     */
    private function getOrCreateBalance(Employee $employee, LeaveType $leaveType, ?int $year = null): LeaveBalance
    {
        $year ??= now()->year;

        return LeaveBalance::firstOrCreate(
            [
                'employee_id' => $employee->id,
                'leave_type_id' => $leaveType->id,
                'year' => $year,
            ],
            [
                'used_days' => 0,
                'total_days' => $this->shouldUseEmployeeAnnualDays($leaveType) ? $employee->annual_leave_days : null,
            ]
        );
    }

    private function assertLeaveEligibility(Employee $employee, LeaveType $leaveType): void
    {
        if ($leaveType->gender !== 'all' && $leaveType->gender !== $employee->gender) {
            throw new \InvalidArgumentException(
                __('messages.gender_not_eligible_for_leave', ['gender' => __('messages.' . $leaveType->gender)])
            );
        }
    }

    private function assertLeaveBalanceAvailable(
        Employee $employee,
        LeaveType $leaveType,
        Carbon $startDate,
        int $daysRequested,
        ?array $credit = null
    ): void {
        $balance = $this->getOrCreateBalance($employee, $leaveType, $startDate->year);
        $maxDays = $balance->total_days ?? $this->resolveAllowedDays($employee, $leaveType);

        if ($maxDays <= 0) {
            return;
        }

        $remaining = $maxDays - (float) $balance->used_days;

        if (
            $credit
            && (int) $credit['leave_type_id'] === $leaveType->id
            && (int) $credit['year'] === $startDate->year
        ) {
            $remaining += (float) $credit['days'];
        }

        if ($daysRequested > $remaining) {
            throw new \InvalidArgumentException(
                __('messages.insufficient_leave_balance', ['remaining' => max(0, (int) floor($remaining))])
            );
        }
    }

    private function resolveAllowedDays(Employee $employee, LeaveType $leaveType): int
    {
        if ($this->shouldUseEmployeeAnnualDays($leaveType) && $employee->annual_leave_days > 0) {
            return (int) $employee->annual_leave_days;
        }

        return (int) $leaveType->max_days_per_year;
    }

    private function shouldUseEmployeeAnnualDays(LeaveType $leaveType): bool
    {
        return (bool) preg_match('/Annual|سنوي|Annual Leave|إجازة سنوية/i', $leaveType->name);
    }

    private function adjustBalanceUsage(Employee $employee, LeaveType $leaveType, int $year, float $deltaDays): void
    {
        if ($deltaDays === 0.0) {
            return;
        }

        $balance = $this->getOrCreateBalance($employee, $leaveType, $year);
        $balance->used_days = max(0, (float) $balance->used_days + $deltaDays);

        if ($this->shouldUseEmployeeAnnualDays($leaveType) && $employee->annual_leave_days > 0) {
            $balance->total_days = $employee->annual_leave_days;
        }

        $balance->save();
    }

    private function syncApprovedBalanceAfterUpdate(LeaveRequest $leaveRequest, array $oldValues): void
    {
        if (($oldValues['status'] ?? null) === 'approved') {
            $oldLeaveType = LeaveType::find($oldValues['leave_type_id']);

            if ($oldLeaveType) {
                $this->adjustBalanceUsage(
                    $leaveRequest->employee,
                    $oldLeaveType,
                    (int) Carbon::parse($oldValues['start_date'])->year,
                    -((float) ($oldValues['days_count'] ?? 0))
                );
            }
        }

        if ($leaveRequest->status === 'approved') {
            $this->adjustBalanceUsage(
                $leaveRequest->employee,
                $leaveRequest->leaveType,
                $leaveRequest->start_date->year,
                $leaveRequest->days_count
            );
        }
    }

    private function resolveStatusAction(string $currentStatus, string $action): string
    {
        return match ($action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'postpone' => 'postponed',
            default => $currentStatus,
        };
    }

    private function determineUpdateAction(array $oldValues, LeaveRequest $leaveRequest, string $statusAction): string
    {
        if ($statusAction === 'approve') {
            return 'approved';
        }

        if ($statusAction === 'reject') {
            return 'rejected';
        }

        if ($statusAction === 'postpone') {
            return 'postponed';
        }

        $resumptionChanged =
            ($oldValues['resumption_at'] ?? null) !== optional($leaveRequest->resumption_at)->toDateTimeString()
            || ($oldValues['resumption_notes'] ?? null) !== $leaveRequest->resumption_notes;

        if ($resumptionChanged) {
            return 'resumption_updated_by_company';
        }

        return 'updated_by_company';
    }

    private function snapshot(LeaveRequest $leaveRequest): array
    {
        return [
            'leave_type_id' => $leaveRequest->leave_type_id,
            'leave_type_name' => $leaveRequest->leaveType?->name,
            'start_date' => optional($leaveRequest->start_date)->toDateString(),
            'end_date' => optional($leaveRequest->end_date)->toDateString(),
            'days_count' => $leaveRequest->days_count,
            'status' => $leaveRequest->status,
            'reason' => $leaveRequest->reason,
            'reviewer_comment' => $leaveRequest->reviewer_comment,
            'reviewed_at' => optional($leaveRequest->reviewed_at)->toDateTimeString(),
            'resumption_at' => optional($leaveRequest->resumption_at)->toDateTimeString(),
            'resumption_notes' => $leaveRequest->resumption_notes,
        ];
    }

    private function logAction(
        LeaveRequest $leaveRequest,
        string $action,
        ?User $actor = null,
        ?string $notes = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): LeaveRequestAction {
        return LeaveRequestAction::create([
            'leave_request_id' => $leaveRequest->id,
            'user_id' => $actor?->id,
            'employee_id' => $leaveRequest->employee_id,
            'client_id' => $leaveRequest->client_id,
            'actor_name' => $this->resolveActorName($actor, $leaveRequest),
            'actor_role' => $actor?->role ?? 'system',
            'action' => $action,
            'notes' => $notes,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }

    private function resolveActorName(?User $actor, LeaveRequest $leaveRequest): string
    {
        if (!$actor) {
            return 'System';
        }

        if ($actor->isEmployee() && $actor->employee) {
            return $actor->employee->name;
        }

        if ($actor->isClient() && $actor->client) {
            return $actor->client->name;
        }

        return $actor->name;
    }

    private function sendEmployeeDecisionNotification(LeaveRequest $leaveRequest, string $status): void
    {
        $titleKey = $status === 'approved'
            ? 'messages.leave_request_approved'
            : 'messages.leave_request_rejected';

        $messageKey = $status === 'approved'
            ? 'messages.leave_request_approved_msg'
            : 'messages.leave_request_rejected_msg';

        $this->notificationService->createNotification([
            'employee_id' => $leaveRequest->employee_id,
            'type' => 'leave_request_' . $status,
            'title' => $titleKey,
            'message' => json_encode([
                'key' => $messageKey,
                'params' => [
                    'start' => $leaveRequest->start_date->format('d M'),
                    'end' => $leaveRequest->end_date->format('d M'),
                ],
            ]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);
    }

    private function sendEmployeePostponedNotification(LeaveRequest $leaveRequest): void
    {
        $this->notificationService->createNotification([
            'employee_id' => $leaveRequest->employee_id,
            'type' => 'leave_request_postponed',
            'title' => 'messages.leave_request_postponed',
            'message' => json_encode([
                'key' => 'messages.leave_request_postponed_msg',
                'params' => [
                    'start' => $leaveRequest->start_date->format('d M'),
                    'end' => $leaveRequest->end_date->format('d M'),
                ],
            ]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);
    }

    private function sendEmployeeUpdateNotification(LeaveRequest $leaveRequest, string $action, array $oldValues): void
    {
        if (in_array($action, ['approved', 'rejected'], true)) {
            $this->sendEmployeeDecisionNotification($leaveRequest, $action);
            return;
        }

        if ($action === 'postponed') {
            $this->sendEmployeePostponedNotification($leaveRequest);
            return;
        }

        $messageKey = $action === 'resumption_updated_by_company'
            ? 'messages.leave_resumption_adjusted_msg'
            : 'messages.leave_request_updated_msg';

        $this->notificationService->createNotification([
            'employee_id' => $leaveRequest->employee_id,
            'type' => $action,
            'title' => $action === 'resumption_updated_by_company'
                ? 'messages.leave_resumption_adjusted'
                : 'messages.leave_request_updated',
            'message' => json_encode([
                'key' => $messageKey,
                'params' => [
                    'start' => $leaveRequest->start_date->format('d M'),
                    'end' => $leaveRequest->end_date->format('d M'),
                    'old_start' => Carbon::parse($oldValues['start_date'])->format('d M'),
                    'old_end' => Carbon::parse($oldValues['end_date'])->format('d M'),
                ],
            ]),
            'related_type' => LeaveRequest::class,
            'related_id' => $leaveRequest->id,
        ]);
    }
}
