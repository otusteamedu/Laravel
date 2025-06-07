<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\TodoUser;
use App\Models\TodoStatus;
use App\Models\ProjectUser;
use App\Models\TodoRoleEnum;
use Illuminate\Support\Carbon;
use App\Models\ProjectRoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectOwner = ProjectUser::query()
            ->whereJsonContains('roles', ProjectRoleEnum::ADMIN)
            ->inRandomOrder()
            ->first();

        $status_id = TodoStatus::query()
            ->where('project_id', $projectOwner?->project_id)
            ->inRandomOrder()
            ->pluck('id')
            ->first();

        if (!$status_id) {
            $status_id = TodoStatus::factory()->create();
        }

        return [
            'title'       => fake()->sentence(),
            'author_id'   => $projectOwner->user_id,
            'project_id'  => $projectOwner->project_id,
            'status_id'   => $status_id,
            'description' => fake()->paragraph(),
            'deadline'    => Carbon::instance(fake()->dateTimeBetween('1 week', '1 month')),
            'options'     => fake()->randomElement([['isHot' => true], []]),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Todo $todo) {
            TodoUser::factory()->create([
                'todo_id' => $todo->id,
                'user_id' => $todo->author_id,
                'role'    => TodoRoleEnum::RESPONSIBLE,
            ]);
        });
    }
}
