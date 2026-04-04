<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ClientController;

Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::patch('/clients/{client}/status', [ClientController::class, 'updateStatus'])->name('clients.status');
    Route::patch('/clients/{client}/subscription', [ClientController::class, 'updateSubscription'])->name('clients.subscription');
});
