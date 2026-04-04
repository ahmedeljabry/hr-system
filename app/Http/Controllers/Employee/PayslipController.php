<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Payslip;

class PayslipController extends Controller
{
    private function getEmployeeId(): int
    {
        $employee = auth()->user()->employee;
        abort_unless($employee, 404);
        return $employee->id;
    }

    public function index()
    {
        $employeeId = $this->getEmployeeId();
        $payslips = Payslip::where('employee_id', $employeeId)
            ->whereHas('payrollRun', fn($q) => $q->where('status', 'confirmed'))
            ->with('payrollRun')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('employee.payslips.index', compact('payslips'));
    }

    public function show(int $payslip)
    {
        $employeeId = $this->getEmployeeId();
        $payslip = Payslip::where('employee_id', $employeeId)
            ->with(['payrollRun', 'lineItems', 'employee'])
            ->findOrFail($payslip);

        return view('employee.payslips.show', compact('payslip'));
    }
}
