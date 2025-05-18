<?php

namespace Database\Seeders;

use App\Models\TodoUser;
use Illuminate\Database\Seeder;

class TodoUserSeeder extends Seeder
{
    /**
     * Добавление пользователей к задачам
     * @return void
     */
    public function run(int $count = 1): void
    {
        TodoUser::factory()
            ->count($count)
            ->create();
    }
}
