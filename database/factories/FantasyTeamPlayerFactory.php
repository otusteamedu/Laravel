<?php

namespace Database\Factories;

use App\Models\FantasyTeam;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FantasyTeamPlayer>
 */
class FantasyTeamPlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'player_id' => null,
            'fantasy_team_id' => null,
            'price' => fake()->numberBetween(10_000, 1_000_000),
        ];
    }
}
