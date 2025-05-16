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

        \App\Models\User::factory(5)->create();

        $this->call([
            TasksTableSeeder::class,
        ]);
    }
}
