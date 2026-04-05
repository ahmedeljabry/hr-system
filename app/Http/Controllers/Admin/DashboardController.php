<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminStatsService;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(
        private AdminStatsService $adminStatsService,
        private DashboardService $dashboardService
    ) {}

    /**
     * Display the admin dashboard with system statistics.
     */
    public function index()
    {
        $stats = $this->adminStatsService->getStats();
        
        $trends = [
            'clients' => $this->dashboardService->getTrendData('clients'),
            'employees' => $this->dashboardService->getTrendData('employees'),
            'users' => $this->dashboardService->getTrendData('users'),
        ];

        return view('admin.dashboard', compact('stats', 'trends'));
    }
}