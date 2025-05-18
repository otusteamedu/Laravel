<?php

namespace Database\Factories;

use App\Models\TodoUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoComment>
 */
class TodoCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $todoUsers = TodoUser::query()
            ->inRandomOrder()
            ->first();

        return [
            'todo_id' => $todoUsers?->todo_id,
            'user_id' => $todoUsers?->user_id,
            'comment' => fake()->paragraph(),
        ];
    }
}
