<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SalaryDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeductionController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()->employee->id;
        
        $deductions = SalaryDeduction::where('employee_id', $employeeId)
            ->latest('deduction_date')
            ->paginate(15);

        return view('employee.deductions.index', compact('deductions'));
    }
}
