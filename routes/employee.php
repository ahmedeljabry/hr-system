<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:employee'])->prefix('employee')->group(function () {
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    });

    Route::get('/tasks', function () {
        return response('Employee tasks', 200); // Placeholder
    });
});
