<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use App\Models\Priority;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Task::factory()->count(10)->create();
    }
}
