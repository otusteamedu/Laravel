<?php

namespace Database\Seeders;

use App\Infrastructure\Eloquent\Models\Category;
use App\Infrastructure\Eloquent\Models\Product;
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
