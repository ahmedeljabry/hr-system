<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InsuranceCompany;

class InsuranceCompanyController extends Controller
{
    public function index()
    {
        $companies = InsuranceCompany::latest()->get();
        return view('admin.insurance-companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:insurance_companies,name',
        ]);

        InsuranceCompany::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', __('messages.company_added_successfully'));
    }

    public function destroy(InsuranceCompany $insurance_company)
    {
        // Check if there are medical insurances linked to this company
        if ($insurance_company->medicalInsurances()->count() > 0) {
            return redirect()->back()->with('error', __('messages.company_has_active_policies'));
        }

        $insurance_company->delete();
        return redirect()->back()->with('success', __('messages.company_deleted_successfully'));
    }
}
