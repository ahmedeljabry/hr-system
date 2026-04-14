<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $roles Comma-separated list of allowed roles
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $allowedRoles = explode(',', $roles);

        // 1. If the user is a Super Admin, always allow access to Admin routes
        // This handles cases where the user just finished impersonating and is redirected back.
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // 2. If the user is currently being impersonated by an Admin
        if (\Illuminate\Support\Facades\Session::has('impersonated_by_admin')) {
            if (in_array($user->role, $allowedRoles)) {
                return $next($request);
            }
            if (in_array('super_admin', $allowedRoles)) {
                return $next($request);
            }
            abort(403, __('messages.unauthorized'));
        }

        // 3. Standard Role Check for normal users (Clients, Employees)
        if (in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, __('messages.unauthorized'));
    }
}
