<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'company_id' => null,
            'title' => $this->faker->word() . ' 面接',
            'type' => '面接',
            'start_at' => '2026-06-10 14:00:00',
            'end_at' => '2026-06-10 15:00:00',
            'location' => 'オンライン',
        ];
    }
}
