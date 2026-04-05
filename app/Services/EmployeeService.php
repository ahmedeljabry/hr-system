<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeService
{
    public function list(int $clientId, ?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Employee::where('client_id', $clientId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('national_id_number', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    public function find(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)->findOrFail($employeeId);
    }

    public function create(int $clientId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null): Employee
    {
        $data['client_id'] = $clientId;

        if ($nationalIdFile) {
            $data['national_id_image'] = $nationalIdFile->store("employees/{$clientId}/national_ids", 'private');
        }
        if ($contractFile) {
            $data['contract_image'] = $contractFile->store("employees/{$clientId}/contracts", 'private');
        }

        return Employee::create($data);
    }

    public function update(int $clientId, int $employeeId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null): Employee
    {
        $employee = $this->find($clientId, $employeeId);

        if ($nationalIdFile) {
            if ($employee->national_id_image) {
                Storage::disk('private')->delete($employee->national_id_image);
            }
            $data['national_id_image'] = $nationalIdFile->store("employees/{$clientId}/national_ids", 'private');
        }
        if ($contractFile) {
            if ($employee->contract_image) {
                Storage::disk('private')->delete($employee->contract_image);
            }
            $data['contract_image'] = $contractFile->store("employees/{$clientId}/contracts", 'private');
        }

        $employee->update($data);
        return $employee->fresh();
    }

    public function delete(int $clientId, int $employeeId): bool
    {
        $employee = $this->find($clientId, $employeeId);
        
        if ($employee->user_id) {
            $user = \App\Models\User::find($employee->user_id);
            if ($user) {
                $user->delete();
            }
        }
        
        return $employee->delete(); // Soft delete
    }
}
