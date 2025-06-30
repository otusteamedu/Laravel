<?php

namespace Database\Factories;

use App\Models\Todolist;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'todolist_id' => Todolist::factory(),
            'author_id' => User::factory(),
            'text' => fake()->sentence,

        ];
    }
}
