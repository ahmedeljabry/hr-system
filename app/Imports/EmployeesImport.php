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

    public function model(array $row): Employee
    {
        return new Employee([
            'client_id' => $this->clientId,
            'name' => $row['name'],
            'position' => $row['position'],
            'national_id_number' => $row['national_id_number'],
            'basic_salary' => $row['basic_salary'],
            'hire_date' => $row['hire_date'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'national_id_number' => ['required', 'string', 'max:100'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'name.required' => __('messages.employee_name') . ' ' . __('validation.required'),
        ];
    }
}
