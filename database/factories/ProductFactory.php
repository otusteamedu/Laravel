<?php

namespace Database\Factories;

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
        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'category_id' => 1,
            'brand_id' => 1,
            'price' => $this->faker->numberBetween(10000, 100000),
            'stock' => $this->faker->numberBetween(1, 100),
            'rating' => $this->faker->randomFloat(2, 3.00, 9.95),
            'ram' => $this->faker->randomElement([2, 4, 6, 8]),
            'builtin_memory' => $this->faker->randomElement([32, 64, 128]),
            'screen_size' => $this->faker->randomFloat(1, 5.0, 7.0),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
