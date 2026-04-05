<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminStatsService;

class DashboardController extends Controller
{
    public function __construct(private AdminStatsService $adminStatsService) {}

    /**
     * Display the admin dashboard with system statistics.
     */
    public function index()
    {
        $stats = $this->adminStatsService->getStats();
        return view('admin.dashboard', compact('stats'));
    }
}