<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Recipe;
use App\Infrastructure\EloquentModels\Tag;
use Illuminate\Database\Seeder;

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
            $recipe->tags()->attach($tag);
        }
    }
}
