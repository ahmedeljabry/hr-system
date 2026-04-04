<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ReminderPhraseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_key' => 'system.event.' . $this->faker->unique()->word,
            'text_en' => $this->faker->sentence(),
            'text_ar' => 'رسالة ' . $this->faker->word,
        ];
    }
}
