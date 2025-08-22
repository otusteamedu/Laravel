<?php

namespace Database\Seeders;

use App\Infrastructure\EloquentModels\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Video::factory()->count(10)->create();
    }
}
