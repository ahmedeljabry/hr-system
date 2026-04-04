<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existing = User::where('email', 'admin@hr-system.com')->first();

        if ($existing) {
            $this->command->warn('مدير النظام موجود بالفعل / Super Admin already exists.');
            return;
        }

        User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@hr-system.com',
            'password' => 'password', // Auto-hashed by model cast if using Laravel 11 'hashed'
            'role' => 'super_admin',
        ]);

        $this->command->info('تم إنشاء مدير النظام بنجاح / Super Admin created successfully.');
    }
}
