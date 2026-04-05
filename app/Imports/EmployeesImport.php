<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\Importable;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors, Importable;

    public function __construct(private int $clientId)
    {
    }

    public function model(array $row): ?Employee
    {
        // Headers are sluggified by Maatwebsite\Excel: 
        // e.g. "Employee Name" -> "employee_name", "National ID / Residency Number" -> "national_id_residency_number"
        
        // Ensure user is created first
        $user = \App\Models\User::firstOrCreate(
            ['email' => $row['email_address']],
            [
                'name' => $row['employee_name'],
                'password' => $row['password'], 
                'role' => 'employee',
                'client_id' => $this->clientId,
            ]
        );

        return new Employee([
            'client_id' => $this->clientId,
            'user_id' => $user->id,
            'name' => $row['employee_name'],
            'position' => $row['position'],
            'national_id_number' => $row['national_id_residency_number'],
            'phone' => $row['phone_number'] ?? null,
            'emergency_phone' => $row['emergency_phone'] ?? null,
            'bank_iban' => $row['bank_iban'] ?? null,
            'basic_salary' => $row['basic_salary'],
            'housing_allowance' => $row['housing_allowance'] ?? 0,
            'transportation_allowance' => $row['transportation_allowance'] ?? 0,
            'other_allowances' => $row['other_allowances'] ?? 0,
            'date_of_birth' => \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTimeFormatCode($row['date_of_birth']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth']) : $row['date_of_birth'] ?? null,
            'hire_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTimeFormatCode($row['hire_date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['hire_date']) : $row['hire_date'],
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_name' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_residency_number' => ['required', 'string', 'max:100'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => __('messages.employee_name') . ' ' . __('validation.required'),
        ];
    }
}
