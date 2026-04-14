<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\SalaryDeduction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DeductionController extends Controller
{
    public function index(Request $request)
    {
        $query = SalaryDeduction::where('client_id', Auth::user()->client_id)
            ->with('employee');

        if ($request->filled('month')) {
            $month = Carbon::parse($request->month);
            $query->whereYear('deduction_date', $month->year)
                  ->whereMonth('deduction_date', $month->month);
        }

        $deductions = $query->latest('deduction_date')->paginate(15);
        
        return view('client.deductions.index', compact('deductions'));
    }

    public function create()
    {
        $employees = Employee::where('client_id', Auth::user()->client_id)->get();
        return view('client.deductions.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => [
                'required',
                \Illuminate\Validation\Rule::exists('employees', 'id')->where('client_id', Auth::user()->client_id),
            ],
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'nullable|string|max:255',
            'deduction_date' => 'required|date',
        ]);

        $employee = Employee::where('client_id', Auth::user()->client_id)->findOrFail($request->employee_id);
        $deductionDate = Carbon::parse($request->deduction_date)->startOfMonth();

        // Check cumulative total for the month
        $existingTotal = SalaryDeduction::where('employee_id', $employee->id)
            ->whereYear('deduction_date', $deductionDate->year)
            ->whereMonth('deduction_date', $deductionDate->month)
            ->sum('amount');

        if (($existingTotal + $request->amount) > $employee->basic_salary) {
            $remaining = max(0, $employee->basic_salary - $existingTotal);
            return back()->withInput()->with('error', __('messages.discount_limit_error_total', [
                'total' => number_format($employee->basic_salary, 2),
                'existing' => number_format($existingTotal, 2),
                'remaining' => number_format($remaining, 2)
            ]));
        }

        $deduction = SalaryDeduction::create([
            'employee_id' => $request->employee_id,
            'client_id' => Auth::user()->client_id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'deduction_date' => $deductionDate,
        ]);

        // Send notification to employee
        Notification::create([
            'employee_id' => $employee->id,
            'type' => 'salary_deduction',
            'title' => json_encode(['key' => 'messages.new_discount_notification_title']),
            'message' => json_encode([
                'key' => 'messages.new_discount_notification_msg',
                'params' => ['amount' => number_format($request->amount, 2)]
            ]),
            'related_type' => SalaryDeduction::class,
            'related_id' => $deduction->id,
        ]);

        return redirect()->route('client.deductions.index')->with('success', __('messages.discount_created'));
    }

    public function destroy(SalaryDeduction $deduction)
    {
        // Ensure the deduction belongs to the authenticated client
        abort_unless($deduction->client_id === Auth::user()->client_id, 403);

        $deduction->delete();

        return redirect()->route('client.deductions.index')
            ->with('success', __('messages.deduction_deleted'));
    }
}
