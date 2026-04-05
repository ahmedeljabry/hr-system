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

        // SuperAdmin can access admin routes, but should be redirected
        // away from client/employee routes where they lack tenant context
        if ($user->isSuperAdmin()) {
            if (in_array('super_admin', $allowedRoles)) {
                return $next($request);
            }
            // Redirect to admin dashboard instead of crashing on missing client
            return redirect('/admin/dashboard');
        }

        if (in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, __('messages.unauthorized'));
    }
}
