<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::whereIn('email', ['admin@example.com', 'editor@example.com'])->get()->pluck('id');

        return [
            'title' => fake()->sentence(),
            'alias' => fake()->unique()->word(),
            'text' => fake()->paragraph(),
            'image' => fake()->imageUrl(),
            'is_sale' => fake()->boolean(70),
            'published' => 1,
            'order' => fake()->numberBetween(0, 1000),
            'price' => fake()->randomFloat(100, 100000),
            'user_id' => $userIds->random(),
        ];
    }
}
