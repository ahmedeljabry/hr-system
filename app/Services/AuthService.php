<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Register a new client and their company.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $client = Client::create([
                'name' => $data['company_name'],
                'subscription_start' => now(),
                'subscription_end' => null,
                'status' => 'active',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'], // Already hashed via model casts in Laravel 11 if using 'hashed'
                'role' => 'client',
                'client_id' => $client->id,
            ]);

            return $user;
        });
    }

    /**
     * Get the dashboard route based on user role.
     */
    public function getDashboardRoute(User $user): string
    {
        return match ($user->role) {
            'super_admin' => '/admin/dashboard',
            'client' => '/client/dashboard',
            'employee' => '/employee/dashboard',
            default => '/login',
        };
    }
}
