<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Task;
use App\Models\Asset;
use App\Models\Payslip;
use App\Models\Announcement;

class DashboardService
{
    public function getWidgetData(Employee $employee): array
    {
        return [
            'pending_tasks' => Task::where('employee_id', $employee->id)->whereIn('status', ['todo', 'in_progress'])->count(),
            'assigned_assets' => Asset::where('employee_id', $employee->id)->count(),
            'latest_payslip' => Payslip::where('employee_id', $employee->id)->latest()->first(),
            'recent_announcements' => Announcement::where('client_id', $employee->client_id)->latest('published_at')->take(3)->get(),
            'leave_balance' => null, // Placeholder for Phase 4
        ];
    }
}
