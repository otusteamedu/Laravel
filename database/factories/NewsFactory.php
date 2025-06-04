<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        $userId = $user->id ?? User::factory()->create()->id;

        $category = Category::inRandomOrder()->first();
        $categoryId = $category->id ?? Category::factory()->create()->id;

        return [
            'user_id' => $userId,
            'category_id' => $categoryId,
            'title' => fake()->sentence,
            'content' => fake()->text,
            'published_at' => fake()->dateTimeBetween('+1 day', '+1 month'),
            'is_draft' => fake()->boolean(70),
        ];
    }
}
