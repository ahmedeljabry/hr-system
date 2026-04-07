<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectTo(
            guests: '/login',
            users: function () {
                $user = auth()->user();
                if (!$user) return '/login';

                if ($user->role === 'super_admin') return '/admin/dashboard';
                if ($user->role === 'client') {
                    $slug = $user->client->slug ?? 'client';
                    return "/$slug/dashboard";
                }
                if ($user->role === 'employee' && $user->client && $user->employee) {
                    $cSlug = $user->client->slug;
                    $eSlug = $user->employee->slug;
                    return "/$cSlug/$eSlug/dashboard";
                }
                return '/login';
            }
        );

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->trustProxies(at: '*');
 
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check_subscription' => \App\Http\Middleware\CheckSubscriptionStatus::class,
            'client_tenant' => \App\Http\Middleware\HandleClientTenant::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
