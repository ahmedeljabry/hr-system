<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionRequiredController extends Controller
{
    private function getClient()
    {
        return Auth::user()->client;
    }

    public function index()
    {
        $client = $this->getClient();

        // 1. Rejected Leave Requests
        $rejectedLeaves = LeaveRequest::where('client_id', $client->id)
            ->where('status', 'rejected')
            ->with('employee', 'leaveType')
            ->latest()
            ->get();

        // 2. Overdue Tasks (Todo or In Progress but past due date)
        $overdueTasks = Task::where('client_id', $client->id)
            ->whereIn('status', ['todo', 'in_progress'])
            ->where('due_date', '<', now()->startOfDay())
            ->with('employee')
            ->latest()
            ->get();

        // 3. Returned Assets (Items that were returned and might need review or deletion for cleanup)
        // Note: The user mentioned "postponed", which for assets usually means assignments that are over but still in the list
        $returnedAssets = Asset::where('client_id', $client->id)
            ->whereNotNull('returned_date')
            ->with('employee')
            ->latest()
            ->get();

        return view('client.action-required.index', compact('rejectedLeaves', 'overdueTasks', 'returnedAssets'));
    }

    public function destroyLeave(LeaveRequest $leaveRequest)
    {
        abort_unless($leaveRequest->client_id === $this->getClient()->id, 403);
        $leaveRequest->delete();
        return redirect()->back()->with('success', __('messages.leave_request_deleted'));
    }

    public function destroyTask(Task $task)
    {
        abort_unless($task->client_id === $this->getClient()->id, 403);
        $task->delete();
        return redirect()->back()->with('success', __('messages.task_deleted'));
    }

    public function destroyAsset(Asset $asset)
    {
        abort_unless($asset->client_id === $this->getClient()->id, 403);
        $asset->delete();
        return redirect()->back()->with('success', __('messages.asset_deleted'));
    }
}
