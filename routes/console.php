<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

use Illuminate\Support\Facades\Hash;

Artisan::command('speckit:create-super-admin {email?} {password?}', function ($email = null, $password = null) {
    $email = $email ?? env('SUPER_ADMIN_EMAIL', 'admin@example.com');
    $password = $password ?? env('SUPER_ADMIN_PASSWORD', 'SuperSecret123');

    $this->comment("Creating super admin: {$email}");

    $existing = \App\Models\User::where('email', $email)->first();
    if ($existing && $existing->role === 'super_admin') {
        $this->comment('Super admin already exists.');
        return;
    }

    if ($existing) {
        $existing->update(['role' => 'super_admin']);
        $this->info('Upgraded existing user to super_admin.');
        return;
    }

    $user = \App\Models\User::create([
        'name' => 'Super Admin',
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'super_admin',
    ]);

    $this->info('Super admin created successfully.');
})->describe('Create initial super admin user');

\Illuminate\Support\Facades\Schedule::command('insurance:update-status')->daily();
