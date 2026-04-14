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
        if (app()->environment('production')) {
            $this->app->bind('path.public', function () {
                return base_path();
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Vite::useBuildDirectory('build');

        \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
            $view->with('admin_badges', [
                'pending_clients' => \App\Models\Client::where('status', 'suspended')->count(),
            ]);
        });

        \Illuminate\Support\Facades\View::composer('layouts.employee', function ($view) {
            if (auth()->check() && auth()->user()->employee) {
                $service = app(\App\Services\NotificationService::class);
                $view->with('employee_notifications_count', $service->getUnreadCount(auth()->user()->employee->id, false));
            } else {
                $view->with('employee_notifications_count', 0);
            }
        });

        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (auth()->check() && auth()->user()->client) {
                $service = app(\App\Services\NotificationService::class);
                $view->with('client_notifications_count', $service->getUnreadCount(auth()->user()->client->id, true));
            } else {
                $view->with('client_notifications_count', 0);
            }
        });
    }
}
