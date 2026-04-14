<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $leaveService) {}

    private function getClient(): Client
    {
        $tenantClient = request()->attributes->get('current_client');
        if ($tenantClient instanceof Client) {
            return $tenantClient;
        }

        $user = Auth::user();
        $client = $user?->client;

        if (!$client && $user?->client_id) {
            $client = Client::find($user->client_id);
        }

        abort_unless((bool) $client, 403, __('messages.client_not_found') ?: 'Could not determine your company. Please log in again.');

        return $client;
    }

    /**
     * Leave requests list with optional status filter.
     */
    public function index(Request $request)
    {
        $client = $this->getClient();
        $filters = $request->only(['status', 'sort', 'direction', 'search']);
        if ($request->boolean('needs_resumption')) {
            $filters['needs_resumption'] = true;
        }

        $requests = $this->leaveService->getAllRequests($client, $filters);
        $pendingCount = LeaveRequest::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'postponed'])
            ->count();
        $resumptionCount = LeaveRequest::where('client_id', $client->id)
            ->where('status', 'approved')
            ->whereNull('resumption_at')
            ->whereDate('end_date', '<', now()->toDateString())
            ->count();

        return view('client.leaves.index', compact('requests', 'filters', 'pendingCount', 'resumptionCount'));
    }

    /**
     * Edit/manage a leave request.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        $client = $this->getClient();
        abort_unless($leaveRequest->client_id === $client->id, 403, __('messages.unauthorized'));

        $leaveRequest->load(['employee', 'leaveType', 'actions.user', 'actions.employee']);
        $leaveTypes = $this->leaveService->getLeaveTypes($client);

        return view('client.leaves.edit', compact('leaveRequest', 'leaveTypes'));
    }

    /**
     * Update/manage a leave request.
     */
    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $client = $this->getClient();
        abort_unless($leaveRequest->client_id === $client->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'leave_type_id' => ['required', 'exists:leave_types,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
            'status_action' => ['required', Rule::in(['keep', 'approve', 'reject', 'postpone'])],
            'reviewer_comment' => ['nullable', 'string', 'max:1000', 'required_if:status_action,reject,postpone'],
            'resumption_at' => ['nullable', 'date'],
            'resumption_notes' => ['nullable', 'string', 'max:1000'],
            'clear_resumption' => ['nullable', 'boolean'],
        ]);

        try {
            $this->leaveService->updateByCompany($leaveRequest, $data, Auth::user());

            return redirect()
                ->route('client.leaves.edit', $leaveRequest)
                ->with('success', __('messages.leave_request_updated_successfully'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Leave types management.
     */
    public function types()
    {
        $client = $this->getClient();
        $leaveTypes = $this->leaveService->getLeaveTypes($client);

        return view('client.leaves.types', compact('leaveTypes'));
    }

    /**
     * Store a new leave type.
     */
    public function storeType(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'max_days_per_year' => 'required|integer|min:0|max:365',
            'gender' => 'required|in:male,female,all',
        ]);

        $this->leaveService->createLeaveType($this->getClient(), $data);

        return redirect()->route('client.leaves.types')->with('success', __('messages.leave_type_created'));
    }

    /**
     * Update a leave type.
     */
    public function updateType(Request $request, LeaveType $leaveType)
    {
        $client = $this->getClient();
        abort_unless($leaveType->client_id === $client->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'max_days_per_year' => 'required|integer|min:0|max:365',
            'gender' => 'required|in:male,female,all',
        ]);

        $this->leaveService->updateLeaveType($leaveType, $data);

        return redirect()->route('client.leaves.types')->with('success', __('messages.leave_type_updated'));
    }

    /**
     * Delete a leave type.
     */
    public function destroyType(LeaveType $leaveType)
    {
        $client = $this->getClient();
        abort_unless($leaveType->client_id === $client->id, 403, __('messages.unauthorized'));

        $this->leaveService->deleteLeaveType($leaveType);

        return redirect()->route('client.leaves.types')->with('success', __('messages.leave_type_deleted'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $client = $this->getClient();
        abort_unless($leaveRequest->client_id === $client->id, 403, __('messages.unauthorized'));

        try {
            $data = $request->validate([
                'reviewer_comment' => ['nullable', 'string', 'max:1000'],
            ]);

            $this->leaveService->approve($leaveRequest, $data['reviewer_comment'] ?? null, Auth::user());
            return redirect()->back()->with('success', __('messages.leave_approved'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $client = $this->getClient();
        abort_unless($leaveRequest->client_id === $client->id, 403, __('messages.unauthorized'));

        try {
            $data = $request->validate([
                'reviewer_comment' => ['required', 'string', 'max:1000'],
            ]);

            $this->leaveService->reject($leaveRequest, $data['reviewer_comment'], Auth::user());
            return redirect()->back()->with('success', __('messages.leave_rejected'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Postpone a leave request.
     */
    public function postpone(Request $request, LeaveRequest $leaveRequest)
    {
        $client = $this->getClient();
        abort_unless($leaveRequest->client_id === $client->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'reviewer_comment' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $this->leaveService->postpone($leaveRequest, $data['reviewer_comment'], Auth::user());
            return redirect()->back()->with('success', __('messages.leave_postponed'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
