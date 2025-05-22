<?php

namespace Database\Seeders;

use App\Models\ProjectUser;
use App\Models\ProjectRoleEnum;
use Illuminate\Database\Seeder;

class ProjectUserSeeder extends Seeder
{
    /**
     * Добавление пользователей к проектам
     * @return void
     */
    public function run(int $count = 1): void
    {
        for ($i = 0; $i < $count; $i++) {
            ProjectUser::factory(['roles' => [ProjectRoleEnum::MEMBER]])
                ->create();
        }
    }
}
