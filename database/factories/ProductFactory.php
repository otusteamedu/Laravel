<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Infrastructure\Eloquent\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Eloquent\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
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
            'image' => fake()->imageUrl(),
            'is_sale' => fake()->boolean(70),
            'published' => 1,
            'order' => fake()->numberBetween(0, 1000),
            'price' => fake()->randomFloat(2, 100, 100000),
            'user_id' => User::factory(),
        ];
    }
}
