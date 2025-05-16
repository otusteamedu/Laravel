<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritiesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $priorities = ['Low', 'Medium', 'High'];

        foreach ($priorities as $priorityName) {
            Priority::factory()->create([
                'name' => $priorityName
            ]);
        }
    }
}
