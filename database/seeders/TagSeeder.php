<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::factory()->count(10)->create();
        for ($i = 0; $i < 20; $i++) {
            $tag = Tag::inRandomOrder()->first()->id;
            $recipe = Recipe::inRandomOrder()->first();
            $recipe->tag()->attach($tag);
        }
    }
}
