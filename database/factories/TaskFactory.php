<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'completed', 'overdue'];
        $priorities = ['low', 'medium', 'high'];

        return [
            'user_id' => User::factory(), // Создаст нового пользователя или свяжет с существующим
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph( 2, variableNbSentences: true),
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the task is completed.
     */
    public function completed(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'due_date' => $this->faker->dateTimeBetween('-1 month', 'now'), // Выполнена недавно
        ]);
    }

    /**
     * Indicate that the task is overdue.
     */
    public function overdue(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'overdue',
            'due_date' => $this->faker->dateTimeBetween('-1 year', '-1 month'), // Дата выполнения в прошлом
        ]);
    }
}
