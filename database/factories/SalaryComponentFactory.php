<?php

namespace Database\Factories;

use App\Models\SalaryComponent;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaryComponentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'type' => fake()->randomElement(['allowance', 'deduction']),
            'name' => fake()->randomElement(['Housing', 'Transport', 'Insurance', 'Tax', 'Food']),
            'amount' => fake()->randomFloat(2, 100, 3000),
        ];
    }

    public function allowance(): static
    {
        return $this->state(['type' => 'allowance']);
    }

    public function deduction(): static
    {
        return $this->state(['type' => 'deduction']);
    }
}
