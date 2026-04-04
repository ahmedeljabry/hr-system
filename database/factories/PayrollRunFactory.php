<?php

namespace Database\Factories;

use App\Models\PayrollRun;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayrollRunFactory extends Factory
{
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'month' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-01'),
            'status' => 'draft',
        ];
    }

    public function confirmed(): static
    {
        return $this->state([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }
}
