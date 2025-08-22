<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Photo;
use Illuminate\Database\Seeder;

class PhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Photo::factory()->count(20)->create();
    }
}
