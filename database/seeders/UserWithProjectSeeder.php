<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Infrastructure\Eloquent\Models\Project;
use App\TodoApp\Infrastructure\Eloquent\Models\ProjectUser;

class UserWithProjectSeeder extends Seeder
{
    /**
     * Добавление пользователей с проектами
     * @return void
     */
    public function run(int $count = 1): void
    {
        $users = User::factory()
            ->has(UserProfile::factory(), 'profile')
            ->count($count)
            ->create();

        foreach ($users as $user) {
            Project::factory()
                ->has(ProjectUser::factory([
                    'user_id'    => $user->id,
                    'roles'      => [ProjectRoleEnum::ADMIN],
                    'invited_at' => now(),
                    'joined_at'  => now(),
                    'left_at'    => null
                ]), 'projectUsers')
                ->create();
        }
    }
}
