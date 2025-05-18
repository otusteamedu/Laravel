<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Добавление проекта
     * @return void
     */
    public function run(int $count = 1): void
    {
        Project::factory()
            ->count($count)
            ->create();
    }
}
