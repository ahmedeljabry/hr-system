<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Super admins are not tied to a single client subscription status regarding restricted access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $client = $user->client;
        
        if (!$client || !$client->isActive()) {
            return redirect('/subscription/renewal');
        }

        return $next($request);
    }
}
