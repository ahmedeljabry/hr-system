<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class EmployeeAccountController extends Controller
{
    public function create(int $employee)
    {
        $clientId = auth()->user()->client->id;
        $emp = Employee::where('client_id', $clientId)->findOrFail($employee);
        return view('client.employees.create-account', ['employee' => $emp]);
    }

    public function store(Request $request, int $employee)
    {
        $clientId = auth()->user()->client->id;
        $emp = Employee::where('client_id', $clientId)->findOrFail($employee);

        if ($emp->user_id) {
            return redirect()
                ->route('client.employees.show', $emp->id)
                ->with('error', __('messages.employee_already_has_account'));
        }

        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $emp->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => 'employee',
        ]);

        $emp->update(['user_id' => $user->id]);

        return redirect()
            ->route('client.employees.show', $emp->id)
            ->with('success', __('messages.employee_account_created', ['password' => $password]));
    }
}
