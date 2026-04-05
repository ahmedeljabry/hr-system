<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Production Super Admin
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'super_admin'
            ]
        );

        // Optional: First Template Client to start with
        if (\App\Models\Client::count() === 0) {
            $client = \App\Models\Client::create([
                'name' => 'Default Organization',
                'status' => 'active',
                'subscription_start' => now(),
                'subscription_end' => now()->addYear(),
            ]);

            \App\Models\User::create([
                'name' => 'Admin User',
                'email' => 'client@client.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'client',
                'client_id' => $client->id,
            ]);
        }
    }
}
