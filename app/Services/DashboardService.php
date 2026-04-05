<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Asset;
use App\Models\Payslip;
use App\Models\Announcement;
use App\Models\LeaveBalance;
use App\Models\LeaveType;

class DashboardService
{
    public function getWidgetData(Employee $employee): array
    {
        // Calculate total remaining leave balance
        $leaveTypes = LeaveType::where('client_id', $employee->client_id)->get();
        $year = now()->year;
        $totalLeaveRemaining = 0;

        foreach ($leaveTypes as $type) {
            if ($type->max_days_per_year > 0) {
                $balance = LeaveBalance::where('employee_id', $employee->id)
                    ->where('leave_type_id', $type->id)
                    ->where('year', $year)
                    ->first();
                $used = $balance ? (float) $balance->used_days : 0;
                $totalLeaveRemaining += max(0, $type->max_days_per_year - $used);
            }
        }

        return [
            'pending_tasks' => Task::where('employee_id', $employee->id)->whereIn('status', ['todo', 'in_progress'])->count(),
            'assigned_assets' => Asset::where('employee_id', $employee->id)->count(),
            'latest_payslip' => Payslip::where('employee_id', $employee->id)->latest()->first(),
            'recent_announcements' => Announcement::where('client_id', $employee->client_id)->latest('published_at')->take(3)->get(),
            'leave_balance' => $totalLeaveRemaining,
        ];
    }
}
