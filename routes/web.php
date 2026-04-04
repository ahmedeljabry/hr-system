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

// Root Redirect
Route::get('/', function () {
    return view('welcome');
});

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
});

// Subscription Public Renewal (Redirect endpoint)
Route::get('/subscription/renewal', function () {
    return response('Your subscription is not active. Please contact admin.', 200);
})->name('subscription.renewal');

// Admin Routes (Separated)
require __DIR__.'/admin.php';

// Client Routes (Separated)
require __DIR__.'/client.php';

// Employee Routes (Separated)
require __DIR__.'/employee.php';
