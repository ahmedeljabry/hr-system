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

use Maatwebsite\Excel\Concerns\WithStartRow;

class EmployeesImport implements ToModel, WithValidation, SkipsOnFailure, SkipsOnError, WithStartRow
{
    use SkipsFailures, SkipsErrors, Importable;

    public function __construct(private int $clientId)
    {
    }

    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row): ?Employee
    {
        // Skip empty or mostly empty rows
        if (empty(array_filter($row))) {
            return null;
        }

        // $row is now a numeric array (Column A = $row[0], Column B = $row[1]...)
        
        $email = $row[2];
        if (!$email) return null;

        $user = \App\Models\User::firstOrCreate(
            ['email' => $email], // Column C: Email Address
            [
                'name' => $row[1] ?? $row[0], // Column B: Name (En)
                'password' => $row[3], // Column D: Password
                'role' => 'employee',
                'client_id' => $this->clientId,
            ]
        );

        return new Employee([
            'client_id' => $this->clientId,
            'user_id' => $user->id,
            'name_ar' => $row[0], // Column A
            'name_en' => $row[1], // Column B
            'email' => $row[2], // Column C
            'position' => $row[4], // Column E: Position
            'national_id_number' => $row[5], // Column F: National ID
            'phone' => $row[6] ?? null, // Column G: Phone
            'emergency_phone' => $row[7] ?? null, // Column H: Emergency
            'bank_iban' => $row[8] ?? null, // Column I: IBAN
            'basic_salary' => $this->sanitizeNumber($row[9]), // Column J: Basic Salary
            'housing_allowance' => $this->sanitizeNumber($row[10]) ?? 0, // Column K
            'transportation_allowance' => $this->sanitizeNumber($row[11]) ?? 0, // Column L
            'other_allowances' => $this->sanitizeNumber($row[12]) ?? 0, // Column M
            'date_of_birth' => $this->parseDate($row[13] ?? null), // Column N
            'hire_date' => $this->parseDate($row[14] ?? null), // Column O
        ]);
    }

    private function sanitizeNumber($value)
    {
        if ($value === null || $value === '') return 0;
        // Strip out anything that's not a digit or a decimal point
        $cleanValue = preg_replace('/[^0-9.]/', '', strval($value));
        return is_numeric($cleanValue) ? floatval($cleanValue) : 0;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTimeFormatCode($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
        }
        return $value;
    }

    public function rules(): array
    {
        return [
            '0' => ['required', 'string', 'max:255'], // Employee Name (Arabic)
            '1' => ['required', 'string', 'max:255'], // Employee Name (English)
            '2' => ['required', 'email'], // Email Address
            '3' => ['required', 'min:8'], // Password
            '4' => ['required', 'string', 'max:255'], // Position
            '5' => ['required', 'max:100'], // National ID
            // We'll validate numeric fields but they might have currency strings, 
            // the sanitizeNumber in model handles it, 
            // but for rules, let's just make it loose or pre-clean.
            '9' => ['required'], // Basic Salary
        ];
    }

    public function customValidationAttributes(): array
    {
        return [
            '0' => __('messages.name_ar'),
            '1' => __('messages.name_en'),
            '2' => __('messages.email'),
            '3' => __('messages.password'),
            '4' => __('messages.position'),
            '5' => __('messages.national_id_number'),
            '9' => __('messages.basic_salary'),
        ];
    }

    public function customValidationMessages(): array
    {
        return [];
    }
}
