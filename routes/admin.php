<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::patch('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus'])->name('clients.status');
    Route::patch('/clients/{client}/subscription', [ClientController::class, 'updateSubscription'])->name('clients.subscription');
    Route::delete('/clients', [ClientController::class, 'bulkDestroy'])->name('clients.bulk-destroy');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::resource('localization', \App\Http\Controllers\Admin\LocalizationDecisionController::class);
    Route::resource('insurance-companies', \App\Http\Controllers\Admin\InsuranceCompanyController::class)->only(['index', 'store', 'destroy']);
    
    // Global Employees management
    Route::get('/employees', [\App\Http\Controllers\Admin\EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/{employee}/edit', [\App\Http\Controllers\Admin\EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [\App\Http\Controllers\Admin\EmployeeController::class, 'update'])->name('employees.update');
    
    // Impersonate Client or Employee
    Route::post('/impersonate/client/{client}', [\App\Http\Controllers\Admin\ImpersonateController::class, 'impersonateClient'])->name('impersonate.client');
    Route::post('/impersonate/employee/{employee}', [\App\Http\Controllers\Admin\ImpersonateController::class, 'impersonateEmployee'])->name('impersonate.employee');
});
