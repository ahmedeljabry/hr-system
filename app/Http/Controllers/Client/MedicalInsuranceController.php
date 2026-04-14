<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\InsuranceCompany;
use App\Models\EmployeeMedicalInsurance;
use Illuminate\Support\Facades\Auth;

class MedicalInsuranceController extends Controller
{
    public function index(Request $request)
    {
        $clientId = Auth::user()->client_id;
        
        $query = Employee::where('client_id', $clientId)
            ->where('status', 'active')
            ->with('medicalInsurance.company');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(10);
        $companies = InsuranceCompany::all();

        return view('client.medical-insurance.index', compact('employees', 'companies'));
    }

    public function assign(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'insurance_company_id' => 'required|exists:insurance_companies,id',
            'policy_number' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $clientId = Auth::user()->client_id;
        
        EmployeeMedicalInsurance::updateOrCreate(
            [
                'client_id' => $clientId,
                'employee_id' => $request->employee_id,
            ],
            [
                'insurance_company_id' => $request->insurance_company_id,
                'policy_number' => $request->policy_number,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]
        );

        return redirect()->back()->with('success', __('messages.insurance_assigned_successfully'));
    }

    public function destroy(EmployeeMedicalInsurance $insurance)
    {
        if ($insurance->client_id !== Auth::user()->client_id) {
            abort(403);
        }

        $insurance->delete();
        return redirect()->back()->with('success', __('messages.insurance_removed_successfully'));
    }
}
