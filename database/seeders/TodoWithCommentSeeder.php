<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\TodoComment;
use Illuminate\Database\Seeder;

class TodoWithCommentSeeder extends Seeder
{
    /**
     * Создание задачи с комментарием автора
     * @return void
     */
    public function run(int $count = 1): void
    {
        Todo::factory()
            ->has(TodoComment::factory()
                ->state(function (array $attributes, Todo $todo) {
                    return [
                        'todo_id' => $todo->id,
                        'author_id' => $todo->author_id,
                    ];
                }), 'comments')
            ->count($count)
            ->create();
    }
}
