<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Client;
use App\Models\Employee;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
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
            'employee_id' => Employee::factory(),
            'type' => $this->faker->randomElement(['Laptop', 'Phone', 'Car']),
            'serial_number' => $this->faker->unique()->bothify('SN-#####'),
            'description' => $this->faker->sentence(),
            'assigned_date' => now()->subDays(10)->format('Y-m-d'),
            'returned_date' => null,
        ];
    }
}
