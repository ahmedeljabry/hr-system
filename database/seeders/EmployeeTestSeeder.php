<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientId = 4;
        $count = 15;

        for ($i = 0; $i < $count; $i++) {
            $employeeData = \App\Models\Employee::factory()->make(['client_id' => $clientId])->toArray();
            
            \Illuminate\Support\Facades\DB::transaction(function () use ($clientId, $employeeData) {
                $user = \App\Models\User::create([
                    'name' => $employeeData['name'],
                    'email' => $employeeData['email'],
                    'password' => 'password', // hashed by User model cast
                    'role' => 'employee',
                    'client_id' => $clientId,
                ]);

                $employeeData['user_id'] = $user->id;
                \App\Models\Employee::create($employeeData);
            });
        }
    }
}
