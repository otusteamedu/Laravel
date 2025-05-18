<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Создание комментариев к задачам
     * @return void
     */
    public function run(int $count = 1): void
    {
        Todo::factory()
            ->count($count)
            ->create();
    }
}
