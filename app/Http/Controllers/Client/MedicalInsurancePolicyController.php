<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeMedicalInsurance;
use App\Models\InsuranceCompany;
use App\Models\InsurancePolicy;
use App\Imports\InsurancePolicyImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\BulkInsurancePolicyImport;

class MedicalInsurancePolicyController extends Controller
{
    public function bulkImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new BulkInsurancePolicyImport();
            Excel::import($import, $request->file('excel_file'));
            
            if ($import->rowsProcessed === 0) {
                return redirect()->back()->with('error', __('messages.invalid_excel_template'));
            }
            
            $msg = __('messages.import_policy_success', ['count' => $import->rowsProcessed]);
            if (count($import->skippedRows) > 0) {
                $msg .= __('messages.import_skipped_employees', ['count' => count($import->skippedRows)]);
            }
            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function index(Request $request)
    {
        $clientId = Auth::user()->client_id;
        $query = InsurancePolicy::where('client_id', $clientId)
            ->with(['insuranceCompany', 'employeeInsurances'])
            ->withCount('employees');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('policy_number', 'like', "%{$search}%")
                  ->orWhereHas('insuranceCompany', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $policies = $query->paginate(10);

        return view('client.medical-insurance.policies.index', compact('policies'));
    }

    public function create()
    {
        $clientId = Auth::user()->client_id;
        $companies = InsuranceCompany::all(); // Global companies
        $employees = Employee::where('client_id', $clientId)->where('status', 'active')->get();

        return view('client.medical-insurance.policies.create', compact('companies', 'employees'));
    }

    public function store(Request $request)
    {
        $clientId = Auth::user()->client_id;

        $request->validate([
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'policy_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('insurance_policies')->where(function ($query) use ($clientId) {
                    return $query->where('client_id', $clientId);
                }),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'assigned_employees' => 'nullable|array',
            'assigned_employees.*.id' => [
                'required',
                'exists:employees,id',
                function ($attribute, $value, $fail) use ($clientId) {
                    if (!Employee::where('id', $value)->where('client_id', $clientId)->exists()) {
                        $fail(__('messages.unauthorized'));
                    }
                },
            ],
            'assigned_employees.*.class' => 'required|string',
            'assigned_employees.*.cost' => 'required|numeric|min:0',
        ]);

        $policy = InsurancePolicy::create([
            'client_id' => $clientId,
            'insurance_company_id' => $request->insurance_company_id,
            'policy_number' => $request->policy_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        if ($request->has('assigned_employees')) {
            foreach ($request->assigned_employees as $emp) {
                EmployeeMedicalInsurance::updateOrCreate([
                    'client_id' => $clientId,
                    'employee_id' => $emp['id'],
                    'insurance_policy_id' => $policy->id,
                ], [
                    'insurance_class' => $emp['class'],
                    'cost' => $emp['cost'],
                ]);
            }
        }

        return redirect()->route('client.medical-insurance.policies.index')
            ->with('success', __('messages.policy_created'));
    }

    public function show(InsurancePolicy $medical_insurance_policy)
    {
        if ($medical_insurance_policy->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $medical_insurance_policy->load(['insuranceCompany', 'employees' => function($q) {
            $q->where('status', 'active');
        }]);
        
        return view('client.medical-insurance.policies.show', [
            'policy' => $medical_insurance_policy
        ]);
    }

    public function edit(InsurancePolicy $medical_insurance_policy)
    {
        if ($medical_insurance_policy->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $clientId = Auth::user()->client_id;
        $companies = InsuranceCompany::all();
        $employees = Employee::where('client_id', $clientId)->where('status', 'active')->get();
        $medical_insurance_policy->load('employeeInsurances');

        return view('client.medical-insurance.policies.edit', [
            'policy' => $medical_insurance_policy,
            'companies' => $companies,
            'employees' => $employees,
        ]);
    }

    public function update(Request $request, InsurancePolicy $medical_insurance_policy)
    {
        if ($medical_insurance_policy->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $clientId = Auth::user()->client_id;

        $request->validate([
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'policy_number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('insurance_policies')->where(function ($query) use ($clientId) {
                    return $query->where('client_id', $clientId);
                })->ignore($medical_insurance_policy->id),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'assigned_employees' => 'nullable|array',
            'assigned_employees.*.id' => [
                'required',
                'exists:employees,id',
                function ($attribute, $value, $fail) use ($clientId) {
                    if (!Employee::where('id', $value)->where('client_id', $clientId)->exists()) {
                        $fail(__('messages.unauthorized'));
                    }
                },
            ],
            'assigned_employees.*.class' => 'required|string',
            'assigned_employees.*.cost' => 'required|numeric|min:0',
        ]);

        $medical_insurance_policy->update([
            'insurance_company_id' => $request->insurance_company_id,
            'policy_number' => $request->policy_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        // Sync logic: Identify which employees to keep, add, or remove
        $assignedEmployeeIds = [];
        if ($request->has('assigned_employees')) {
            foreach ($request->assigned_employees as $emp) {
                $assignedEmployeeIds[] = $emp['id'];
                EmployeeMedicalInsurance::updateOrCreate([
                    'client_id' => $clientId,
                    'employee_id' => $emp['id'],
                    'insurance_policy_id' => $medical_insurance_policy->id,
                ], [
                    'insurance_class' => $emp['class'],
                    'cost' => $emp['cost'],
                ]);
            }
        }

        // Remove employees not in the new list
        $medical_insurance_policy->employeeInsurances()
            ->whereNotIn('employee_id', $assignedEmployeeIds)
            ->delete();

        return redirect()->route('client.medical-insurance.policies.index')
            ->with('success', __('messages.policy_updated'));
    }

    public function destroy(InsurancePolicy $medical_insurance_policy)
    {
        if ($medical_insurance_policy->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $medical_insurance_policy->delete();

        return redirect()->route('client.medical-insurance.policies.index')
            ->with('success', __('messages.policy_deleted'));
    }

    public function import(Request $request, InsurancePolicy $policy)
    {
        if ($policy->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new InsurancePolicyImport($policy->id);
            Excel::import($import, $request->file('excel_file'));
            
            if ($import->rowsProcessed === 0) {
                return redirect()->back()->with('error', __('messages.invalid_excel_template'));
            }
            
            $msg = __('messages.import_success', ['count' => $import->rowsProcessed]);
            if (count($import->skippedRows) > 0) {
                $msg .= __('messages.import_skipped_employees', ['count' => count($import->skippedRows)]);
            }
            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
