<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = ['red', 'blue', 'green', 'yellow', 'orange'];

        foreach ($colors as $color) {
            Category::factory()->create([
                'color' => $color
            ]);
        }
    }
}
