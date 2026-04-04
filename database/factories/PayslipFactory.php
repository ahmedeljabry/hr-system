<?php

namespace Database\Factories;

use App\Models\Payslip;
use App\Models\PayrollRun;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayslipFactory extends Factory
{
    public function definition(): array
    {
        $basic = fake()->randomFloat(2, 3000, 15000);
        $allowances = fake()->randomFloat(2, 500, 5000);
        $deductions = fake()->randomFloat(2, 100, 2000);

        return [
            'payroll_run_id' => PayrollRun::factory(),
            'employee_id' => Employee::factory(),
            'basic_salary' => $basic,
            'total_allowances' => $allowances,
            'total_deductions' => $deductions,
            'net_salary' => $basic + $allowances - $deductions,
        ];
    }
}
