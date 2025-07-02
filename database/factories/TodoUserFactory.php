<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use App\Domain\Repositories\Todo\ValueObject\TodoRoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TodoUser>
 */
class TodoUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::query()
            ->with([
                'userProjects' => function ($q) {
                    $q->whereNotNull('joined_at')
                        ->whereNull('left_at')
                        ->whereJsonContains('roles', ProjectRoleEnum::MEMBER);
                }
            ])
            ->whereHas('userProjects', function ($q) {
                $q->whereNotNull('joined_at')
                    ->whereNull('left_at')
                    ->whereJsonContains('roles', ProjectRoleEnum::MEMBER);
            })
            ->inRandomOrder()
            ->first();

        $todo = Todo::query()
            ->notMember($user)
            ->whereIn('project_id', $user->userProjects->pluck('project_id')->toArray())
            ->first();

        if (!$todo) {
            $todo = Todo::factory([
                'author_id'  => $user->id,
                'project_id' => fake()->randomElement($user->userProjects->pluck('project_id')->toArray()),
            ])
                ->create();
        }

        return [
            'todo_id' => $todo->id,
            'user_id' => $user->id,
            'role'    => fake()->randomElement(TodoRoleEnum::cases()),
        ];
    }
}
