<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function __construct(protected LeaveService $leaveService) {}

    private function getEmployee()
    {
        $employee = Auth::user()->employee;
        abort_unless((bool) $employee, 404, __('Employee profile not found.'));
        return $employee;
    }

    /**
     * Show leave balance + history.
     */
    public function index()
    {
        $employee = $this->getEmployee();
        $balanceSummary = $this->leaveService->getBalanceSummary($employee);
        $requests = $this->leaveService->getEmployeeRequests($employee);

        return view('employee.leaves.index', compact('balanceSummary', 'requests'));
    }

    /**
     * Show leave request form.
     */
    public function create()
    {
        $employee = $this->getEmployee();
        $leaveTypes = $this->leaveService->getEligibleLeaveTypes($employee);
        $balanceSummary = $this->leaveService->getBalanceSummary($employee);

        return view('employee.leaves.create', compact('leaveTypes', 'balanceSummary'));
    }

    /**
     * Submit a leave request.
     */
    public function store(Request $request)
    {
        $employee = $this->getEmployee();

        $data = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $this->leaveService->submitRequest($employee, $data);
            return redirect()->route('employee.leaves.index')->with('success', __('Leave request submitted successfully.'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
