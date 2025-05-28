<?php

namespace Database\Factories;

use App\Models\TelegramUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FantasyTeam>
 */
class FantasyTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'telegram_user_id' => TelegramUser::inRandomOrder()->first()?->id ?? TelegramUser::factory(),
            'name' => fake()->company(),
            'budget' => fake()->numberBetween(10_000, 1_000_000),
            'points' => fake()->numberBetween(0, 1_000),
        ];
    }
}
