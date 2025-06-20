<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PrioritiesTableSeeder::class,
            CategoriesTableSeeder::class,
        ]);

        // Создаем администратора первым
        \App\Models\User::factory()->admin()->create();
        
        // Создаем обычных пользователей
        \App\Models\User::factory(4)->create();

        $this->call([
            TasksTableSeeder::class,
        ]);
    }
}
