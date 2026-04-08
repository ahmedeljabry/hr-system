<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user()->client;

        if (!$client) {
            // Super_admin accessing client routes — redirect to admin dashboard
            return redirect()->route('admin.dashboard');
        }

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

        return view('client.dashboard', compact(
            'client',
            'employeeCount',
            'genderStats',
            'ageStats',
            'daysUntilExpiry',
            'showExpiryWarning',
            'recentAnnouncements',
        ));
    }
}
