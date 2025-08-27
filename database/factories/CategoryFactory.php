<?php

namespace Database\Factories;

use App\Infrastructure\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Infrastructure\Eloquent\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Eloquent\Models\Category>
 */
class CategoryFactory extends Factory
{

    protected $model = Category::class;
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
