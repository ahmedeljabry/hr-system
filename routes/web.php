<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Redirect to Login
Route::get('/', function () {
    return redirect()->route('login.show');
});

// Manual Cache Clear (Helper for Hostinger)
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    return "Cache is cleared! <a href='/admin/dashboard'>Go back to Dashboard</a>";
})->middleware(['auth', 'role:super_admin']);

// Temporary Route to run migrations on Hostinger
Route::get('/run-migrations', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "Migrations ran successfully! <a href='/admin/dashboard'>Go back to Dashboard</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
})->middleware(['auth', 'role:super_admin']);

// Localization Switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switch']);

// Guest Routes (Auth)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::get('/login', [LoginController::class, 'show'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

// Auth-only Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/leave-impersonation', [\App\Http\Controllers\Admin\ImpersonateController::class, 'leaveImpersonation'])->name('impersonate.leave');
});

// Subscription Public Renewal (Redirect endpoint)
Route::get('/subscription/renewal', function () {
    return response('Your subscription is not active. Please contact admin.', 200);
})->name('subscription.renewal');

// Redirect Legacy Employee Dashboard to Slug-based Dashboard
Route::get('/employee/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->role === 'employee' && $user->client && $user->employee) {
        return redirect()->to("/" . $user->client->slug . "/" . $user->employee->slug . "/dashboard");
    }
    return redirect('/login');
})->middleware('auth');

// Redirect Legacy Client Dashboard to Slug-based Dashboard
Route::get('/client/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->role === 'client' && $user->client) {
        return redirect()->to("/" . $user->client->slug . "/dashboard");
    }
    return redirect('/login');
})->middleware('auth');

// Admin Routes (Separated)
require __DIR__.'/admin.php';

// Client Routes (Separated)
require __DIR__.'/client.php';

// Employee Routes (Separated)
require __DIR__.'/employee.php';
Route::get('/clear-cache', function() {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    return 'Cache cleared successfully!';
});

Route::get('/run-migrations', function() {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    return 'Migrations ran successfully!';
});