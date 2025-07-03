<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory()
            ->count(5)
            ->create()
            ->each(function (Category $category) {
                Product::factory()
                    ->count(fake()->numberBetween(5, 20))
                    ->hasAttached($category)
                    ->create();
            });

    }
}
