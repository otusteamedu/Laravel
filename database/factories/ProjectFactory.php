<?php

namespace Database\Factories;

use App\Models\TodoStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\TodoApp\Infrastructure\Eloquent\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence,
            'description' => fake()->paragraph,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Project $project) {
            TodoStatus::factory()->create([
                'project_id' => $project->id,
                'name'       => 'Новая',
                'sort'       => 10,
                'color'      => '#ffc107'
            ]);

            TodoStatus::factory()->create([
                'project_id' => $project->id,
                'name'       => 'В работе',
                'sort'       => 20,
                'color'      => '#0dcaf0'
            ]);

            TodoStatus::factory()->create([
                'project_id' => $project->id,
                'name'       => 'Завершена',
                'sort'       => 30,
                'color'      => '#198754'
            ]);

            TodoStatus::factory()->create([
                'project_id' => $project->id,
                'name'       => 'Архив',
                'sort'       => 40,
                'color'      => '#f8f9fa'
            ]);
        });
    }
}
