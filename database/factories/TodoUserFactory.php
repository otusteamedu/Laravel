<?php

namespace Database\Factories;

use App\Models\Todo;
use App\Models\User;
use App\Enums\TodoRoleEnum;
use App\Enums\ProjectRoleEnum;
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
                'activeProjects' => function ($q) {
                    $q->whereJsonContains('roles', ProjectRoleEnum::MEMBER);
                }
            ])
            ->whereHas('activeProjects', function ($q) {
                $q->whereJsonContains('roles', ProjectRoleEnum::MEMBER);
            })
            ->first();

        $todo = Todo::query()
            ->notMember($user)
            ->whereIn('project_id', $user->projects->pluck('id')->toArray())
            ->first();

        return [
            'todo_id' => $todo->id,
            'user_id' => $user->id,
            'roles'   => [fake()->randomElement(TodoRoleEnum::cases())],
        ];
    }
}
