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

    Route::get('/employees/import', [EmployeeController::class, 'importForm'])->name('employees.import.form');
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');

    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

    // Salary Components
    Route::get('/employees/{employee}/salary-components', [\App\Http\Controllers\Client\SalaryComponentController::class, 'index'])->name('salary-components.index');
    Route::post('/employees/{employee}/salary-components', [\App\Http\Controllers\Client\SalaryComponentController::class, 'store'])->name('salary-components.store');
    Route::put('/employees/{employee}/salary-components/{component}', [\App\Http\Controllers\Client\SalaryComponentController::class, 'update'])->name('salary-components.update');
    Route::delete('/employees/{employee}/salary-components/{component}', [\App\Http\Controllers\Client\SalaryComponentController::class, 'destroy'])->name('salary-components.destroy');

    // Payroll
    Route::get('/payroll', [\App\Http\Controllers\Client\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/run', [\App\Http\Controllers\Client\PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll/run', [\App\Http\Controllers\Client\PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payrollRun}', [\App\Http\Controllers\Client\PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payrollRun}/confirm', [\App\Http\Controllers\Client\PayrollController::class, 'confirm'])->name('payroll.confirm');


    // Secure file serving (tenant-scoped)
    Route::get('/files/employees/{employee}/{type}', [EmployeeFileController::class, 'show'])->name('files.employee');

    // Operational Management (Attendance, Tasks, Assets)
    Route::get('/attendance', [\App\Http\Controllers\Client\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Client\AttendanceController::class, 'store'])->name('attendance.store');
    Route::resource('tasks', \App\Http\Controllers\Client\TaskController::class);
    Route::resource('assets', \App\Http\Controllers\Client\AssetController::class);
    Route::resource('announcements', \App\Http\Controllers\Client\AnnouncementController::class);

    // Leave Management
    Route::get('/leaves', [\App\Http\Controllers\Client\LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/types', [\App\Http\Controllers\Client\LeaveController::class, 'types'])->name('leaves.types');
    Route::post('/leaves/types', [\App\Http\Controllers\Client\LeaveController::class, 'storeType'])->name('leaves.store-type');
    Route::put('/leaves/types/{leaveType}', [\App\Http\Controllers\Client\LeaveController::class, 'updateType'])->name('leaves.update-type');
    Route::delete('/leaves/types/{leaveType}', [\App\Http\Controllers\Client\LeaveController::class, 'destroyType'])->name('leaves.destroy-type');
    Route::post('/leaves/{leaveRequest}/approve', [\App\Http\Controllers\Client\LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leaveRequest}/reject', [\App\Http\Controllers\Client\LeaveController::class, 'reject'])->name('leaves.reject');
});

