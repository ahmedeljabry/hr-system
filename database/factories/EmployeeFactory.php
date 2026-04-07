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
            'name_ar' => fake('ar_SA')->name(),
            'name_en' => fake('en_US')->name(),
            'gender' => fake()->randomElement(['male', 'female']),
            'email' => fake()->unique()->safeEmail(),
            'position' => fake()->jobTitle(),
            'national_id_number' => fake()->unique()->numerify('##########'),
            'phone' => fake()->unique()->numerify('05########'),
            'emergency_phone' => fake()->numerify('05########'),
            'bank_iban' => fake()->iban('SA'),
            'basic_salary' => fake()->randomFloat(2, 3000, 15000),
            'housing_allowance' => 0.00,
            'transportation_allowance' => 0.00,
            'other_allowances' => 0.00,
            'annual_leave_days' => 21,
            'hire_date' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
        ];
    }
}
