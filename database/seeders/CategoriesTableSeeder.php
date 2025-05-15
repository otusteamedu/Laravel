<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arColors = ['red', 'blue', 'green', 'yellow', 'orange'];
        for ($i = 0; $i < count($arColors); $i++) {
            DB::table('categories')->insert(
                [
                    'color'       => $arColors[$i],
                    'name'        => fake()->sentence(),
                    'description' => fake()->paragraph()
                ]
            );
        }
    }
}
