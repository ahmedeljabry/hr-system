<?php

namespace App\Services;

use App\Models\PayrollRun;
use App\Models\Payslip;
use App\Models\PayslipLineItem;
use App\Models\Employee;
use App\Models\SalaryDeduction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PayrollService
{
    public function getHistory(int $clientId)
    {
        return PayrollRun::where('client_id', $clientId)
            ->withCount('payslips')
            ->orderByDesc('month')
            ->paginate(15);
    }

    public function findRun(int $clientId, int $runId): PayrollRun
    {
        return PayrollRun::where('client_id', $clientId)
            ->with(['payslips.employee', 'payslips.lineItems'])
            ->findOrFail($runId);
    }

    public function runPayroll(int $clientId, string $month): PayrollRun
    {
        $monthDate = Carbon::parse($month)->startOfMonth();

        // Prevent future months
        if ($monthDate->isAfter(now()->startOfMonth())) {
            throw new \InvalidArgumentException(__('messages.payroll_future_month'));
        }

        // Prevent duplicate confirmed runs
        $existing = PayrollRun::where('client_id', $clientId)
            ->whereDate('month', $monthDate)
            ->where('status', 'confirmed')
            ->exists();

        if ($existing) {
            throw new \InvalidArgumentException(__('messages.payroll_duplicate'));
        }

        return DB::transaction(function () use ($clientId, $monthDate) {
            $run = PayrollRun::create([
                'client_id' => $clientId,
                'month' => $monthDate->format('Y-m-d'),
                'status' => 'draft',
            ]);

            $employees = Employee::where('client_id', $clientId)
                ->with('salaryComponents')
                ->get();

            foreach ($employees as $employee) {
                $allowances = $employee->salaryComponents->where('type', 'allowance');
                $deductions = $employee->salaryComponents->where('type', 'deduction');

                $housingAllowance = (float) $employee->housing_allowance;
                $transportationAllowance = (float) $employee->transportation_allowance;
                $otherAllowancesOnModel = (float) $employee->other_allowances;

                $totalExtraAllowances = $allowances->sum('amount');
                $totalAllowances = $totalExtraAllowances + $housingAllowance + $transportationAllowance + $otherAllowancesOnModel;
                
                // Get dynamic deductions for the current month
                $dynamicDeductions = SalaryDeduction::where('employee_id', $employee->id)
                    ->whereYear('deduction_date', $monthDate->year)
                    ->whereMonth('deduction_date', $monthDate->month)
                    ->get();
                
                $totalDeductions = $deductions->sum('amount') + $dynamicDeductions->sum('amount');
                $basicSalary = (float) $employee->basic_salary;
                $netSalary = max(0, $basicSalary + $totalAllowances - $totalDeductions);

                $payslip = Payslip::create([
                    'payroll_run_id' => $run->id,
                    'employee_id' => $employee->id,
                    'basic_salary' => $basicSalary,
                    'housing_allowance' => $housingAllowance,
                    'transportation_allowance' => $transportationAllowance,
                    'other_allowances' => $otherAllowancesOnModel + $totalExtraAllowances, // Summing dynamic components into other
                    'total_allowances' => $totalAllowances,
                    'total_deductions' => $totalDeductions,
                    'net_salary' => $netSalary,
                ]);

                // Snapshot each component as a line item
                foreach ($employee->salaryComponents as $component) {
                    PayslipLineItem::create([
                        'payslip_id' => $payslip->id,
                        'component_name' => $component->name,
                        'type' => $component->type,
                        'amount' => $component->amount,
                    ]);
                }

                // Snapshot dynamic deductions
                foreach ($dynamicDeductions as $deduction) {
                    PayslipLineItem::create([
                        'payslip_id' => $payslip->id,
                        'component_name' => $deduction->reason ?: __('messages.salary_deduction'),
                        'type' => 'deduction',
                        'amount' => $deduction->amount,
                    ]);
                }
            }

            return $run;
        });
    }

    public function confirmRun(int $clientId, int $runId): PayrollRun
    {
        $run = PayrollRun::where('client_id', $clientId)->findOrFail($runId);

        if ($run->isConfirmed()) {
            throw new \InvalidArgumentException('Payroll run is already confirmed.');
        }

        $run->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        // Mark dynamic deductions as applied for this month
        $monthDate = Carbon::parse($run->month);
        SalaryDeduction::where('client_id', $clientId)
            ->whereYear('deduction_date', $monthDate->year)
            ->whereMonth('deduction_date', $monthDate->month)
            ->update(['is_applied' => true]);

        return $run->fresh();
    }

    public function deleteRun(int $clientId, int $runId): bool
    {
        $run = PayrollRun::where('client_id', $clientId)->findOrFail($runId);

        // Delete dependencies (Payslips will cascade delete line items if configured, but let's be safe)
        return DB::transaction(function () use ($run) {
            foreach ($run->payslips as $payslip) {
                $payslip->lineItems()->delete();
                $payslip->delete();
            }
            return $run->delete();
        });
    }
}
