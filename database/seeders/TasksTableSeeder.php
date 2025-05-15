<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users      = DB::table('users')->pluck('id');
        $priorities = DB::table('priorities')->pluck('id');
        $categories = DB::table('categories')->pluck('id');

        for ($i = 0; $i < 10; $i++) {
            DB::table('tasks')->insert(
                [
                    'title'       => fake()->sentence(),
                    'description' => fake()->paragraph(),
                    'due_date'    => fake()->date(),
                    'priority_id' => $priorities->random(),
                    'category_id' => $categories->random(),
                    'executor_id' => $users->random(),
                    'created_at'  => now(),
                ]
            );
        }
    }
}
