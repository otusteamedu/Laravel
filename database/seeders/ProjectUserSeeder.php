<?php

namespace Database\Seeders;

use App\Models\ProjectUser;
use App\Enums\ProjectRoleEnum;
use Illuminate\Database\Seeder;

class ProjectUserSeeder extends Seeder
{
    /**
     * Добавление пользователей к проектам
     * @return void
     */
    public function run(int $count = 1): void
    {
        ProjectUser::factory(['roles' => [ProjectRoleEnum::MEMBER]])
            ->count($count)
            ->create();
    }
}
