<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //     $table->string('title');
        //            $table->text('text');
        //            $table->string('alias', 150)->unique();
        //            $table->string('img');

        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'title' => fake()->sentence(),
                'text' => fake()->paragraph(),
                'alias' => fake()->unique()->word(),
                'img' => fake()->imageUrl(),
            ];
        }

        DB::table('articles')->insert($data);
    }
}
