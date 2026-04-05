<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'position' => fake()->jobTitle(),
            'national_id_number' => fake()->unique()->numerify('NID#########'),
            'phone' => fake()->phoneNumber(),
            'emergency_phone' => fake()->phoneNumber(),
            'bank_iban' => fake()->iban('SA'),
            'basic_salary' => fake()->randomFloat(2, 3000, 15000),
            'housing_allowance' => fake()->randomFloat(2, 500, 2500),
            'transportation_allowance' => fake()->randomFloat(2, 300, 1000),
            'other_allowances' => fake()->randomFloat(2, 0, 1000),
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
        ];
    }
}
