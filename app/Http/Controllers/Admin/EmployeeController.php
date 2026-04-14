<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of all employees across all clients for super admin.
     */
    public function index(Request $request)
    {
        $sortable = ['name_en', 'name_ar', 'position', 'hire_date', 'client_id'];
        $requestedSort = $request->get('sort') === 'name' ? 'name_en' : $request->get('sort');
        $sort = in_array($requestedSort, $sortable) ? $requestedSort : 'name_en';
        $dir = $request->get('dir') === 'desc' ? 'desc' : 'asc';

        $query = Employee::with(['client', 'user'])->orderBy($sort, $dir);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('national_id_number', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhereHas('client', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $employees = $query->paginate(15)->withQueryString();

        return view('admin.employees.index', compact('employees', 'sort', 'dir'));
    }
    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $employee->load(['client', 'user']);
        return view('admin.employees.edit', compact('employee'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'emergency_phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'nationality' => 'required|string',
            'position' => 'required|string',
            'official_job_title' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'housing_allowance' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'hire_date' => 'required|date',
            'date_of_birth' => 'nullable|date',
            'bank_iban' => 'nullable|string|max:50',
            'residency_number' => 'nullable|string|max:50',
            'residency_start_date' => 'nullable|date',
            'residency_end_date' => 'nullable|date',
            'annual_leave_days' => 'required|integer|min:0',
        ]);

        $employee->update($validated);

        // Update linked user if exists
        if ($employee->user) {
            $employee->user->update([
                'name' => $employee->name_en ?: $employee->name_ar,
                'email' => $employee->email,
            ]);
            
            if (!empty($validated['password'])) {
                $employee->user->update(['password' => $validated['password']]);
            }
        }

        return redirect()->route('admin.employees.index')->with('success', __('messages.employee_updated') ?? 'Employee updated successfully.');
    }
}
