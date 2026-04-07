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

class EmployeesImport implements ToModel, WithValidation, SkipsOnFailure, SkipsOnError, WithHeadingRow
{
    use SkipsFailures, SkipsErrors, Importable;

    public function __construct(private int $clientId)
    {
    }

    public function model(array $row): ?Employee
    {
        // $row is now an associative array with keys like 'employee_name_arabic' 
        // derived from the first row's cells.

        $email = $row['email_address'] ?? null;
        if (!$email) return null;

        $user = \App\Models\User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $row['employee_name_english'] ?? ($row['employee_name_arabic'] ?? 'Employee'),
                'password' => $row['password'] ?? 'password123',
                'role' => 'employee',
                'client_id' => $this->clientId,
            ]
        );

        return new Employee([
            'client_id' => $this->clientId,
            'user_id' => $user->id,
            'name_ar' => $row['employee_name_arabic'] ?? null,
            'name_en' => $row['employee_name_english'] ?? null,
            'email' => $email,
            'gender' => strtolower($row['gender'] ?? 'male'),
            'annual_leave_days' => $this->sanitizeNumber($row['annual_leave_days'] ?? 21),
            'position' => $row['position'] ?? null,
            'national_id_number' => $row['national_id_residency_number'] ?? null,
            'phone' => $row['phone_number'] ?? null,
            'emergency_phone' => $row['emergency_phone'] ?? null,
            'bank_iban' => $row['bank_iban'] ?? null,
            'basic_salary' => $this->sanitizeNumber($row['basic_salary'] ?? 0),
            'housing_allowance' => $this->sanitizeNumber($row['housing_allowance'] ?? 0),
            'transportation_allowance' => $this->sanitizeNumber($row['transportation_allowance'] ?? 0),
            'other_allowances' => $this->sanitizeNumber($row['other_allowances'] ?? 0),
            'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
            'hire_date' => $this->parseDate($row['hire_date'] ?? null),
        ]);
    }

    private function sanitizeNumber($value)
    {
        if ($value === null || $value === '') return 0;
        // Strip out anything that's not a digit or a decimal point
        // Using strval and handling scientific notation safely
        if (is_numeric($value)) return (float)$value;
        $cleanValue = preg_replace('/[^0-9.]/', '', strval($value));
        return is_numeric($cleanValue) ? floatval($cleanValue) : 0;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }
        try {
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'employee_name_arabic' => ['required', 'string', 'max:255'],
            'employee_name_english' => ['required', 'string', 'max:255'],
            'email_address' => ['required', 'email'],
            'gender' => ['required', 'string', 'in:male,female,Male,Female,MALE,FEMALE'],
            'annual_leave_days' => ['required'],
            'password' => ['required', 'min:8'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_residency_number' => ['required', 'max:100'],
            'basic_salary' => ['required'],
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            'employee_name_arabic' => __('messages.name_ar'),
            'employee_name_english' => __('messages.name_en'),
            'email_address' => __('messages.email'),
            'gender' => __('messages.gender'),
            'annual_leave_days' => __('messages.annual_leave_days'),
            'password' => __('messages.password'),
            'position' => __('messages.position'),
            'national_id_residency_number' => __('messages.national_id_number'),
            'basic_salary' => __('messages.basic_salary'),
        ];
    }

    public function customValidationMessages(): array
    {
        return [];
    }
}
