<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\URL;

class HandleClientTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get slugs from the route parameters
        $slug = $request->route('client_slug');
        $employeeSlug = $request->route('employee_slug');

        // 2. NEW: Detect slug from Subdomain if not in path
        if (!$slug) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            if (count($parts) > 2) {
                $slug = $parts[0]; // Gets 'entelaka' from 'entelaka.saddaco.com'
            }
        }

        if (!$slug) {
            return $next($request);
        }

        // 3. Security Check: Verify user belongs to this client and matches employee slug
        $user = $request->user();
        if ($user) {
            $client = $user->client;
            
            // If the user reached /employee/dashboard (old route), redirect to personalized one
            if ($user->role === 'employee' && !$employeeSlug && $client && $client->slug && $user->employee && $user->employee->slug) {
                 return redirect()->to("/$client->slug/" . $user->employee->slug . "/dashboard");
            }

            // Verify Client Slug match
            if ($client && $client->slug && $client->slug !== $slug) {
                 // Force redirect to THEIR proper domain or path
                 if ($user->role === 'employee' && $user->employee && $user->employee->slug) {
                    return redirect()->to("/$client->slug/" . $user->employee->slug . "/dashboard");
                 }
                 return redirect()->to("/$client->slug/dashboard");
            }
        }

        // 4. Set Global URL defaults
        URL::defaults(['client_slug' => $slug]);
        if ($employeeSlug) {
            URL::defaults(['employee_slug' => $employeeSlug]);
        }

        // 5. THE MAGIC: Remove parameters from route so controllers don't see them
        if ($request->route()) {
            $request->route()->forgetParameter('client_slug');
            if ($employeeSlug) {
                $request->route()->forgetParameter('employee_slug');
            }
        }

        return $next($request);
    }
}
