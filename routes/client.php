<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:client', 'check_subscription'])->prefix('client')->group(function () {
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    });

    Route::get('/profile', function () {
        return response('Client profile', 200); // Placeholder
    });
});
