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
        
        // SuperAdmin bypasses all role checks
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $allowedRoles = explode(',', $roles);
        if (in_array($user->role, $allowedRoles)) {
            return $next($request);
        }

        abort(403, __('messages.unauthorized'));
    }
}
