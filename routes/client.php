<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\EmployeeController;
use App\Http\Controllers\Client\EmployeeFileController;

Route::middleware(['auth', 'role:client', 'check_subscription'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');

    // Employee CRUD
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

    Route::get('/employees/import/form', [EmployeeController::class, 'importForm'])->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');

    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Secure file serving (tenant-scoped)
    Route::get('/files/employees/{employee}/{type}', [EmployeeFileController::class, 'show'])->name('files.employee');
});
