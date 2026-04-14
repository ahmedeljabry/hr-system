<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\EmployeeController;
use App\Http\Controllers\Client\EmployeeFileController;

Route::middleware(['auth', 'role:client', 'check_subscription', 'client_tenant'])->prefix('{client_slug}')->name('client.')->group(function () {
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

    // New Termination Routes
    Route::get('/terminated-employees', [EmployeeController::class, 'terminated'])->name('employees.terminated');
    Route::get('/employees/{employee}/terminate', [EmployeeController::class, 'terminationForm'])->name('employees.terminate.form');
    Route::post('/employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');

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
    Route::delete('/payroll/{payrollRun}', [\App\Http\Controllers\Client\PayrollController::class, 'destroy'])->name('payroll.destroy');
    
    // Deductions
    Route::get('/deductions', [\App\Http\Controllers\Client\DeductionController::class, 'index'])->name('deductions.index');
    Route::get('/deductions/create', [\App\Http\Controllers\Client\DeductionController::class, 'create'])->name('deductions.create');
    Route::post('/deductions', [\App\Http\Controllers\Client\DeductionController::class, 'store'])->name('deductions.store');
    Route::delete('/deductions/{deduction}', [\App\Http\Controllers\Client\DeductionController::class, 'destroy'])->name('deductions.destroy');


    // Secure file serving (tenant-scoped)
    Route::get('/files/employees/{employee}/{type}', [EmployeeFileController::class, 'show'])->name('files.employee');
    Route::get('/files/tasks/{task}/attachments/{index}', [\App\Http\Controllers\Client\SecureFileController::class, 'taskAttachment'])->name('files.task.attachment');
    Route::get('/files/announcements/{announcement}/attachments/{index}', [\App\Http\Controllers\Client\SecureFileController::class, 'announcementAttachment'])->name('files.announcement.attachment');

    // Operational Management (Attendance, Tasks, Assets)
    Route::get('/attendance', [\App\Http\Controllers\Client\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [\App\Http\Controllers\Client\AttendanceController::class, 'store'])->name('attendance.store');
    Route::resource('tasks', \App\Http\Controllers\Client\TaskController::class);
    Route::resource('assets', \App\Http\Controllers\Client\AssetController::class);
    Route::resource('announcements', \App\Http\Controllers\Client\AnnouncementController::class);

    // Localization
    Route::get('/localization', [\App\Http\Controllers\Client\LocalizationDecisionController::class, 'index'])->name('localization.index');
    Route::get('/localization/{id}', [\App\Http\Controllers\Client\LocalizationDecisionController::class, 'show'])->name('localization.show');

    // Leave Management
    Route::get('/leaves', [\App\Http\Controllers\Client\LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/{leaveRequest}/edit', [\App\Http\Controllers\Client\LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('/leaves/{leaveRequest}', [\App\Http\Controllers\Client\LeaveController::class, 'update'])->name('leaves.update');
    Route::get('/leaves/types', [\App\Http\Controllers\Client\LeaveController::class, 'types'])->name('leaves.types');
    Route::post('/leaves/types', [\App\Http\Controllers\Client\LeaveController::class, 'storeType'])->name('leaves.store-type');
    Route::put('/leaves/types/{leaveType}', [\App\Http\Controllers\Client\LeaveController::class, 'updateType'])->name('leaves.update-type');
    Route::delete('/leaves/types/{leaveType}', [\App\Http\Controllers\Client\LeaveController::class, 'destroyType'])->name('leaves.destroy-type');
    Route::post('/leaves/{leaveRequest}/approve', [\App\Http\Controllers\Client\LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{leaveRequest}/reject', [\App\Http\Controllers\Client\LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('/leaves/{leaveRequest}/postpone', [\App\Http\Controllers\Client\LeaveController::class, 'postpone'])->name('leaves.postpone');

    // Action Required Management Center
    Route::get('/management/action-required', [\App\Http\Controllers\Client\ActionRequiredController::class, 'index'])->name('action-required.index');
    Route::delete('/management/action-required/leaves/{leaveRequest}', [\App\Http\Controllers\Client\ActionRequiredController::class, 'destroyLeave'])->name('action-required.leaves.destroy');
    Route::delete('/management/action-required/tasks/{task}', [\App\Http\Controllers\Client\ActionRequiredController::class, 'destroyTask'])->name('action-required.tasks.destroy');
    Route::delete('/management/action-required/assets/{asset}', [\App\Http\Controllers\Client\ActionRequiredController::class, 'destroyAsset'])->name('action-required.assets.destroy');

    // Medical Insurance - Redirect old system to new Policies system
    Route::get('/medical-insurance', function() {
        return redirect()->route('client.medical-insurance.policies.index', ['client_slug' => request()->route('client_slug')]);
    })->name('medical-insurance.index');

    // Medical Insurance Policies (New System)
    Route::resource('medical-insurance-policies', \App\Http\Controllers\Client\MedicalInsurancePolicyController::class)->names([
        'index'   => 'medical-insurance.policies.index',
        'create'  => 'medical-insurance.policies.create',
        'store'   => 'medical-insurance.policies.store',
        'show'    => 'medical-insurance.policies.show',
        'edit'    => 'medical-insurance.policies.edit',
        'update'  => 'medical-insurance.policies.update',
        'destroy' => 'medical-insurance.policies.destroy',
    ]);
    Route::post('/medical-insurance-policies/bulk-import', [\App\Http\Controllers\Client\MedicalInsurancePolicyController::class, 'bulkImport'])->name('medical-insurance.policies.bulk-import');
    Route::post('/medical-insurance-policies/{policy}/import', [\App\Http\Controllers\Client\MedicalInsurancePolicyController::class, 'import'])->name('medical-insurance.policies.import');

    // Notifications API
    Route::get('/notifications/api', [\App\Http\Controllers\Client\NotificationController::class, 'api'])->name('notifications.api');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Client\NotificationController::class, 'read'])->name('notifications.read');
});
