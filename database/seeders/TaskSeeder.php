<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create();
        }

        // Для каждого пользователя создадим несколько задач
        foreach ($users as $user) {
            // Создаем 5 случайных задач для текущего пользователя
            Task::factory()->count(5)->create([
                'user_id' => $user->id,
            ]);

            // Создаем 2 завершенные задачи для текущего пользователя
            Task::factory()->count(2)->completed()->create([
                'user_id' => $user->id,
            ]);

            // Создаем 1 просроченную задачу для текущего пользователя
            Task::factory()->count(1)->overdue()->create([
                'user_id' => $user->id,
            ]);

        }

    }
}
