<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonateController extends Controller
{
    /**
     * Impersonate a Client.
     */
    public function impersonateClient(Client $client)
    {
        $clientUser = User::where('client_id', $client->id)->where('role', 'client')->first();
        
        if (!$clientUser) {
            return back()->with('error', __('messages.no_client_user') ?? 'This client does not have a primary login account.');
        }

        $adminId = Auth::id();
        Auth::login($clientUser);
        Session::put('impersonated_by_admin', $adminId);

        return redirect()->to("/$client->slug/dashboard")->with('success', __('messages.impersonating_client') ?? 'You are now logged in as ' . $client->name);
    }

    /**
     * Impersonate an Employee.
     */
    public function impersonateEmployee(\App\Models\Employee $employee)
    {
        $employeeUser = $employee->user;
        
        if (!$employeeUser) {
            return back()->with('error', __('messages.no_employee_user') ?? 'This employee does not have a login account.');
        }

        $adminId = Auth::id();
        Auth::login($employeeUser);
        Session::put('impersonated_by_admin', $adminId);

        $clientSlug = $employee->client->slug;
        $employeeSlug = $employee->slug;

        return redirect()->to("/$clientSlug/$employeeSlug/dashboard")->with('success', __('messages.impersonating_employee') ?? 'You are now logged in as ' . $employee->name);
    }

    /**
     * Leave impersonation and restore super admin session.
     */
    public function leaveImpersonation()
    {
        if (Session::has('impersonated_by_admin')) {
            $adminId = Session::get('impersonated_by_admin');
            $adminUser = User::find($adminId);
            
            Session::forget('impersonated_by_admin');
            
            if ($adminUser && $adminUser->role === 'super_admin') {
                Auth::login($adminUser);
                request()->session()->regenerate();
                return redirect()->route('admin.dashboard')->with('success', __('messages.impersonation_ended') ?? 'Impersonation ended successfully.');
            }
        }
        
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.show');
    }
}
