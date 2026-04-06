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
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('national_id_number', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name_ar')->paginate($perPage);
    }

    public function find(int $clientId, int $employeeId): Employee
    {
        return Employee::where('client_id', $clientId)->findOrFail($employeeId);
    }

    public function create(int $clientId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null, ?UploadedFile $cvFile = null, array $otherFiles = []): Employee
    {
        $data['client_id'] = $clientId;

        if ($nationalIdFile) {
            $data['national_id_image'] = $nationalIdFile->store("employees/{$clientId}/national_ids", 'private');
        }
        if ($contractFile) {
            $data['contract_image'] = $contractFile->store("employees/{$clientId}/contracts", 'private');
        }
        if ($cvFile) {
            $data['cv_file'] = $cvFile->store("employees/{$clientId}/cvs", 'private');
        }

        if (!empty($otherFiles)) {
            $paths = [];
            foreach ($otherFiles as $file) {
                $paths[] = $file->store("employees/{$clientId}/others", 'private');
            }
            $data['other_documents'] = $paths;
        }

        $data['housing_allowance'] = $data['housing_allowance'] ?? 0;
        $data['transportation_allowance'] = $data['transportation_allowance'] ?? 0;
        $data['other_allowances'] = $data['other_allowances'] ?? 0;

        return \Illuminate\Support\Facades\DB::transaction(function () use ($clientId, $data) {
            // Create User account for Employee
            $user = \App\Models\User::create([
                'name' => $data['name_en'],
                'email' => $data['email'],
                'password' => $data['password'], // Hash is handled by cast in User model
                'role' => 'employee',
                'client_id' => $clientId,
            ]);

            $data['user_id'] = $user->id;

            return Employee::create($data);
        });
    }

    public function update(int $clientId, int $employeeId, array $data, ?UploadedFile $nationalIdFile = null, ?UploadedFile $contractFile = null, ?UploadedFile $cvFile = null, array $otherFiles = []): Employee
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
        if ($cvFile) {
            if ($employee->cv_file) {
                Storage::disk('private')->delete($employee->cv_file);
            }
            $data['cv_file'] = $cvFile->store("employees/{$clientId}/cvs", 'private');
        }

        if (!empty($otherFiles)) {
            // Usually we append or replace. For simplicity, replace or merge.
            // Let's assume replace for now, or just append. 
            // Better: let the UI handle deletion of old ones.
            $existingPaths = $employee->other_documents ?? [];
            $newPaths = [];
            foreach ($otherFiles as $file) {
                $newPaths[] = $file->store("employees/{$clientId}/others", 'private');
            }
            $data['other_documents'] = array_merge($existingPaths, $newPaths);
        }

        $data['housing_allowance'] = $data['housing_allowance'] ?? 0;
        $data['transportation_allowance'] = $data['transportation_allowance'] ?? 0;
        $data['other_allowances'] = $data['other_allowances'] ?? 0;

        return \Illuminate\Support\Facades\DB::transaction(function () use ($data, $employee) {
            // Update corresponding User account
            if ($employee->user) {
                $userData = [
                    'name' => $data['name_en'] ?? $employee->user->name,
                    'email' => $data['email'] ?? $employee->user->email,
                ];
                
                if (!empty($data['password'])) {
                    $userData['password'] = $data['password'];
                }
                
                $employee->user->update($userData);
            }

            unset($data['password']);

            $employee->update($data);
            return $employee->fresh();
        });
    }

    public function delete(int $clientId, int $employeeId): bool
    {
        $employee = Employee::where('client_id', $clientId)->findOrFail($employeeId);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($employee) {
            // 1. Delete associated user account if it exists
            if ($employee->user) {
                // Ensure User is hard deleted
                $employee->user->delete();
            }

            // 2. Delete Leave Balances
            \App\Models\LeaveBalance::where('employee_id', $employee->id)->delete();

            // 3. Delete Leave Requests
            \App\Models\LeaveRequest::where('employee_id', $employee->id)->delete();

            // 4. Delete Attendance Records
            \App\Models\Attendance::where('employee_id', $employee->id)->delete();

            // 5. Delete Payslips & Payslip Line Items
            $payslips = \App\Models\Payslip::where('employee_id', $employee->id)->get();
            foreach ($payslips as $payslip) {
                $payslip->lineItems()->delete();
                $payslip->delete();
            }

            // 6. Delete Salary Components
            \App\Models\SalaryComponent::where('employee_id', $employee->id)->delete();

            // 7. Unlink Assets (Asset remains in company but unassigned)
            \App\Models\Asset::where('employee_id', $employee->id)->update(['employee_id' => null]);

            // 8. Unlink Tasks (Task remains but unassigned)
            \App\Models\Task::where('employee_id', $employee->id)->update(['employee_id' => null]);

            // 9. Clean up any files from storage (optional but good practice)
            if ($employee->national_id_image) Storage::disk('private')->delete($employee->national_id_image);
            if ($employee->contract_image) Storage::disk('private')->delete($employee->contract_image);
            if ($employee->cv_file) Storage::disk('private')->delete($employee->cv_file);
            if ($employee->other_documents) {
                foreach ($employee->other_documents as $doc) {
                    Storage::disk('private')->delete($doc);
                }
            }

            // 10. Delete the Employee record itself
            return (bool) $employee->delete();
        });
    }
}
