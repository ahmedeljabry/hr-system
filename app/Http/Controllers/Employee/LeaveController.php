<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
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
    public function index(Request $request)
    {
        $employee = $this->getEmployee();
        $balanceSummary = $this->leaveService->getBalanceSummary($employee);
        $filters = $request->only(['status', 'sort', 'direction']);
        $requests = $this->leaveService->getEmployeeRequests($employee, $filters);
        $currentLeave = $this->leaveService->getCurrentActiveLeave($employee);
        $pendingResumptionLeave = $this->leaveService->getPendingResumptionLeave($employee);

        return view('employee.leaves.index', compact(
            'balanceSummary',
            'requests',
            'filters',
            'currentLeave',
            'pendingResumptionLeave',
        ));
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
            'leave_type_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('leave_types', 'id')->where('client_id', $employee->client_id)
            ],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $this->leaveService->submitRequest($employee, $data, Auth::user());
            return redirect()->route('employee.leaves.index')->with('success', __('Leave request submitted successfully.'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Record return-to-work after leave end.
     */
    public function resume(Request $request, LeaveRequest $leaveRequest)
    {
        $employee = $this->getEmployee();
        abort_unless($leaveRequest->employee_id === $employee->id, 403, __('messages.unauthorized'));

        $data = $request->validate([
            'resumption_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $this->leaveService->recordEmployeeResumption(
                $leaveRequest,
                $employee,
                Auth::user(),
                $data['resumption_notes'] ?? null
            );

            return redirect()->route('employee.leaves.index')->with('success', __('messages.leave_resumption_saved'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
