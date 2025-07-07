<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\TodoApp\Domain\ValueObjects\ProjectRoleEnum;
use App\TodoApp\Infrastructure\Eloquent\Models\ProjectUser;

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
