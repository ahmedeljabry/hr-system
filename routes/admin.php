<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus'])->name('clients.status');
    Route::patch('/clients/{client}/subscription', [ClientController::class, 'updateSubscription'])->name('clients.subscription');
    Route::delete('/clients', [ClientController::class, 'bulkDestroy'])->name('clients.bulk-destroy');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::resource('localization', \App\Http\Controllers\Admin\LocalizationDecisionController::class);
});
