<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => fake()->sentence(),
            'alias' => fake()->unique()->word(),
            'text' => fake()->paragraph(),
            'published' => 1,
            'order' => fake()->numberBetween(0, 1000),
            'user_id' => User::factory()
        ];
    }
}
