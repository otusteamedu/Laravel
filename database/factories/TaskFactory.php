<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{

    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(rand(1, 3)),
            'status' => $this->faker->randomElement(['новая', 'в работе', 'выполнена', 'отменена']),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'priority_id' => Priority::inRandomOrder()->first()->id ?? Priority::factory(),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'executor_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'creator_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
