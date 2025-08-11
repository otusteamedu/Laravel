<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $width=320;
        $height=250;
        for($i=0;$i<20;$i++){
            $name = fake()->name;
            DB::table("news")->insert([
                [
                    'name' => $name,
                    'preview'=> fake()->sentence,
                    'text' => fake()->paragraph,
                    'link'=> Str::slug($name),
                    'user_id'=>(int)DB::table('users')->inRandomOrder()->first()->id,
                    'photo'=> fake()->imageUrl($width, $height),
                    'create_at' => fake()->dateTimeBetween('-1 year', 'now')
                ]
            ]);
        }
    }
}
