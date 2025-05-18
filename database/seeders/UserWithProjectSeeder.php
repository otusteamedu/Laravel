<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\UserProfile;
use App\Enums\ProjectRoleEnum;
use Illuminate\Database\Seeder;

class UserWithProjectSeeder extends Seeder
{
    /**
     * Добавление пользователей с проектами
     * @return void
     */
    public function run(int $count = 1): void
    {
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
            ->count($count)
            ->create();
    }
}
