<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nickname' => fake()->company,
            'name' => fake()->firstName() . ' ' . $this->faker->lastName(),
            'position' => fake()->randomElement(['FWD', 'GK', 'M', 'D']),
            'team_id' => Team::inRandomOrder()->first()?->id ?? Team::factory(),
            'price' => fake()->numberBetween(1_000, 10_000),
            'avatar_path' => fake()->filePath(),
        ];
    }
}
