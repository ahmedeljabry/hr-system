<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryComponent;

class SalaryComponentService
{
    public function getEmployeeComponents(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)
            ->with('salaryComponents')
            ->findOrFail($employeeId);
    }

    public function create(int $clientId, int $employeeId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        return $employee->salaryComponents()->create($data);
    }

    public function update(int $clientId, int $employeeId, int $componentId, array $data): SalaryComponent
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        $component->update($data);
        return $component->fresh();
    }

    public function delete(int $clientId, int $employeeId, int $componentId): bool
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);
        $component = $employee->salaryComponents()->findOrFail($componentId);
        return $component->delete();
    }
}
