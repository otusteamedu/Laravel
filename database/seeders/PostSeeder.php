<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = DB::table("users")->pluck("id");

        DB::table("posts")->insert(
            [
                [
                    "title" => fake()->sentence,
                    "text" => fake()->paragraph,
                    "is_draft" => fake()->boolean(70),
                    "author_id" => $userIds->random()
                ],
                [
                    "title" => fake()->sentence,
                    "text" => fake()->paragraph,
                    "is_draft" => fake()->boolean(70),
                    "author_id" => $userIds->random()
                ]
            ]
        );

    }
}
