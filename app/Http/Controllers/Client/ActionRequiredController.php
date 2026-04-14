<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\LeaveRequest;
use App\Models\Task;
use App\Models\Asset;
use App\Models\EmployeeMedicalInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionRequiredController extends Controller
{
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

    public function index()
    {
        $client = $this->getClient();

        // 1. Leave Requests Needing Review or Return-to-Work Follow-up
        $rejectedLeaves = LeaveRequest::where('client_id', $client->id)
            ->where(function ($query) {
                $query->whereIn('status', ['pending', 'postponed'])
                    ->orWhere(function ($leaveQuery) {
                        $leaveQuery->where('status', 'approved')
                            ->whereNull('resumption_at')
                            ->whereDate('end_date', '<', now()->toDateString());
                    });
            })
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

        // 4. Insurance Expirations
        $insuranceExpirations = EmployeeMedicalInsurance::where('employee_medical_insurances.client_id', $client->id)
            ->join('insurance_policies', 'employee_medical_insurances.insurance_policy_id', '=', 'insurance_policies.id')
            ->where('insurance_policies.end_date', '<=', now()->addDays(30))
            ->where('insurance_policies.end_date', '>=', now()->subDays(30))
            ->where('insurance_policies.status', 'active')
            ->select('employee_medical_insurances.*', 'insurance_policies.end_date as policy_end_date')
            ->with(['employee', 'insurancePolicy.insuranceCompany'])
            ->orderBy('insurance_policies.end_date', 'asc')
            ->get();

        return view('client.action-required.index', compact('rejectedLeaves', 'overdueTasks', 'returnedAssets', 'insuranceExpirations'));
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
