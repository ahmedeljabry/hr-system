<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:employee', 'check_subscription', 'client_tenant'])->prefix('{client_slug}/{employee_slug}')->name('employee.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/payslips', [\App\Http\Controllers\Employee\PayslipController::class, 'index'])->name('payslips.index');
    Route::get('/payslips/{payslip}', [\App\Http\Controllers\Employee\PayslipController::class, 'show'])->name('payslips.show');

    Route::get('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/documents/{type}', [\App\Http\Controllers\Employee\ProfileController::class, 'document'])->name('profile.document')->where('type', 'national_id|contract');

    // Read-Only Portals
    Route::get('/tasks', [\App\Http\Controllers\Employee\TaskController::class, 'index'])->name('tasks.index');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Employee\TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::get('/assets', [\App\Http\Controllers\Employee\AssetController::class, 'index'])->name('assets.index');
    Route::get('/announcements', [\App\Http\Controllers\Employee\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/leaves', [\App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/create', [\App\Http\Controllers\Employee\LeaveController::class, 'create'])->name('leaves.create');
    Route::post('/leaves', [\App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('leaves.store');

    // Deductions
    Route::get('/deductions', [\App\Http\Controllers\Employee\DeductionController::class, 'index'])->name('deductions.index');

    // Notifications API
    Route::get('/notifications/api', [\App\Http\Controllers\Employee\NotificationController::class, 'api'])->name('notifications.api');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Employee\NotificationController::class, 'read'])->name('notifications.read');
});
