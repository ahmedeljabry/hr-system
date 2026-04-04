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
            'position' => fake()->jobTitle(),
            'national_id_number' => fake()->unique()->numerify('NID#########'),
            'basic_salary' => fake()->randomFloat(2, 1000, 20000),
            'hire_date' => fake()->date(),
        ];
    }
}
