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

        $employeeCount = $client->employees()->count();
        $showExpiryWarning = $client->isNearExpiry();
        $daysUntilExpiry = null;

        if ($client->subscription_end) {
            $daysUntilExpiry = (int) now()->startOfDay()->diffInDays($client->subscription_end->copy()->startOfDay(), false);
        }

        return view('client.dashboard', compact(
            'client',
            'employeeCount',
            'daysUntilExpiry',
            'showExpiryWarning',
        ));
    }
}
