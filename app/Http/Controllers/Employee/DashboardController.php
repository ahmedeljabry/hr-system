<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $employee = $request->get('current_employee') ?? auth()->user()->employee;
        if (!$employee) {
            return redirect('/')->with('error', __('Employee profile not found.'));
        }
        
        $widgets = $this->dashboardService->getWidgetData($employee);
        
        return view('employee.dashboard', compact('widgets', 'employee'));
    }
}
