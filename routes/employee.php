<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('dashboard');

    Route::get('/payslips', [\App\Http\Controllers\Employee\PayslipController::class, 'index'])->name('payslips.index');
    Route::get('/payslips/{payslip}', [\App\Http\Controllers\Employee\PayslipController::class, 'show'])->name('payslips.show');
});
