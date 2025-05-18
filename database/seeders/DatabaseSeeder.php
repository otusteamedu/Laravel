<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use App\Models\Project;
use App\Models\TodoUser;
use App\Models\ProjectUser;
use App\Models\TodoComment;
use App\Models\UserProfile;
use App\Enums\ProjectRoleEnum;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(UserProfile::factory(), 'profile')
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        User::factory()
            ->has(UserProfile::factory(), 'profile')
            ->hasAttached(
                Project::factory(),
                [
                    'roles'      => [ProjectRoleEnum::ADMIN],
                    'invited_at' => now(),
                    'joined_at'  => now()
                ]
            )
            ->count(5)
            ->create();

        ProjectUser::factory(['roles' => [ProjectRoleEnum::MEMBER]])
            ->count(10)
            ->create();

        Todo::factory()
            ->has(TodoComment::factory()
                ->state(function (array $attributes, Todo $todo) {
                    return [
                        'todo_id' => $todo->id,
                        'user_id' => $todo->author_id,
                    ];
                }), 'comments')
            ->count(20)
            ->create();

        TodoUser::factory()
            ->count(50)
            ->create();

        TodoComment::factory()
            ->count(50)
            ->create();
    }
}
