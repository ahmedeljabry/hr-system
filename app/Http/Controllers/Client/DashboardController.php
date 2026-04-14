<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $client = $request->get('current_client') ?? auth()->user()->client;

        $employeeCount = $client->employees()->where('status', 'active')->count();

        // Gender Statistics
        $genderStats = [
            'male' => $client->employees()->where('status', 'active')->where('gender', 'male')->count(),
            'female' => $client->employees()->where('status', 'active')->where('gender', 'female')->count(),
        ];

        // Age Statistics
        $employees = $client->employees()->where('status', 'active')->whereNotNull('date_of_birth')->get();
        $ageStats = [
            'under_25' => 0,
            '25_35' => 0,
            '35_45' => 0,
            'over_45' => 0,
        ];

        foreach ($employees as $employee) {
            $age = $employee->date_of_birth->age;
            if ($age < 25) $ageStats['under_25']++;
            elseif ($age <= 35) $ageStats['25_35']++;
            elseif ($age <= 45) $ageStats['35_45']++;
            else $ageStats['over_45']++;
        }

        $showExpiryWarning = $client->isNearExpiry();
        $daysUntilExpiry = null;

        if ($client->subscription_end) {
            $daysUntilExpiry = (int) now()->startOfDay()->diffInDays($client->subscription_end->copy()->startOfDay(), false);
        }

        $recentAnnouncements = $client->announcements()->latest()->take(3)->get();

        // Nationality Statistics (Census)
        $saudiCount = $client->employees()->where('status', 'active')
            ->whereIn('nationality', ['Saudi', 'saudi', 'SA', 'سعودي', 'سعودية'])
            ->count();
        $totalActiveEmployees = $client->employees()->where('status', 'active')->count();
        $otherCount = $totalActiveEmployees - $saudiCount;

        $nationalityStats = [
            'saudi' => $saudiCount,
            'other' => $otherCount,
            'saudi_percentage' => $totalActiveEmployees > 0 ? round(($saudiCount / $totalActiveEmployees) * 100, 1) : 0,
            'other_percentage' => $totalActiveEmployees > 0 ? round(($otherCount / $totalActiveEmployees) * 100, 1) : 0,
        ];

        $actionRequiredCount = \App\Models\LeaveRequest::where('client_id', $client->id)
                ->where(function ($query) {
                    $query->whereIn('status', ['pending', 'postponed'])
                        ->orWhere(function ($leaveQuery) {
                            $leaveQuery->where('status', 'approved')
                                ->whereNull('resumption_at')
                                ->whereDate('end_date', '<', now()->toDateString());
                        });
                })
                ->count() +
            \App\Models\Task::where('client_id', $client->id)->whereIn('status', ['todo', 'in_progress'])->where('due_date', '<', now()->startOfDay())->count() +
            \App\Models\Asset::where('client_id', $client->id)->whereNotNull('returned_date')->count() +
            \App\Models\EmployeeMedicalInsurance::join('insurance_policies', 'employee_medical_insurances.insurance_policy_id', '=', 'insurance_policies.id')
                ->where('employee_medical_insurances.client_id', $client->id)
                ->where('insurance_policies.end_date', '<=', now()->addDays(30))
                ->where('insurance_policies.status', 'active')
                ->count();

        return view('client.dashboard', compact(
            'client',
            'employeeCount',
            'genderStats',
            'ageStats',
            'nationalityStats',
            'daysUntilExpiry',
            'showExpiryWarning',
            'recentAnnouncements',
            'actionRequiredCount',
        ));
    }
}
