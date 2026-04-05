<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip check if user is not authenticated or is super admin
        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $client = $user->client;

        // Active check requires status=active and end_date > now
        if (!$client || !$client->isActive()) {
            return redirect()->route('subscription.renewal');
        }

        return $next($request);
    }
}
