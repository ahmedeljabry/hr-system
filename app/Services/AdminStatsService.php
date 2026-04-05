<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Employee;

class AdminStatsService
{
    /**
     * Get system-wide statistics for admin dashboard.
     *
     * @return array
     */
    public function getStats(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 3600, function () {
            return [
                'total_clients' => Client::count(),
                'total_employees' => Employee::count(),
                'active_count' => Client::where('status', 'active')->count(),
                'suspended_count' => Client::where('status', 'suspended')->count(),
                'expired_count' => Client::where('status', 'expired')->count(),
            ];
        });
    }
}