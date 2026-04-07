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

                return match ($user->role) {
                    'super_admin' => '/admin/dashboard',
                    'client' => '/client/dashboard',
                    'employee' => '/employee/dashboard',
                    default => '/login',
                };
            }
        );

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->trustProxies(at: '*');
 
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'check_subscription' => \App\Http\Middleware\CheckSubscriptionStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
