<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });

        \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
            $view->with('admin_badges', [
                'pending_clients' => \App\Models\Client::where('status', 'suspended')->count(),
            ]);
        });

        \Illuminate\Support\Facades\View::composer('layouts.employee', function ($view) {
            if (auth()->check() && auth()->user()->employee) {
                $service = app(\App\Services\NotificationService::class);
                $view->with('employee_notifications_count', $service->getUnreadCount(auth()->user()->employee->id));
            } else {
                $view->with('employee_notifications_count', 0);
            }
        });
    }
}
