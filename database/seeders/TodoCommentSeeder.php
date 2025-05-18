<?php

namespace Database\Seeders;

use App\Models\TodoComment;
use Illuminate\Database\Seeder;

class TodoCommentSeeder extends Seeder
{
    /**
     * Добавление комментариев к задачам
     * @return void
     */
    public function run(int $count = 1): void
    {
        TodoComment::factory()
            ->count($count)
            ->create();
    }
}
