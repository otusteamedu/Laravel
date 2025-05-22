<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\UserProfile;
use App\Models\ProjectRoleEnum;
use Illuminate\Database\Seeder;

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
