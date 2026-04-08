<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $leaveService) {}

    private function getClient()
    {
        return Auth::user()->client;
    }

    /**
     * Leave requests list with optional status filter.
     */
    public function index(Request $request)
    {
        $client = $this->getClient();
        $status = $request->get('status');
        $requests = $this->leaveService->getAllRequests($client, $status);
        $pendingCount = LeaveRequest::where('client_id', $client->id)->where('status', 'pending')->count();

        return view('client.leaves.index', compact('requests', 'status', 'pendingCount'));
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
            $this->leaveService->approve($leaveRequest, $request->input('reviewer_comment'));
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
            $this->leaveService->reject($leaveRequest, $request->input('reviewer_comment'));
            return redirect()->back()->with('success', __('messages.leave_rejected'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
