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
        $this->call(CategorySeeder::class);
        $this->call(AreaSeeder::class);
        $this->call(RecipeSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(PhotoSeeder::class);
        $this->call(VideoSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(MeasuresSeeder::class);
        $this->call(MeasureProductRecipeSeeder::class);
    }
}
