<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PupleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("puples")->insert(
            [
                [
                    "name" => fake()->name,
                    "surname" => fake()->name,
                    "date_of_birth" => fake()->date(),
                    "mark_math" => random_int(1,5),
                    "mark_literature" => random_int(1,5),
                    "created_at" => now(),
                    "updated_at" => now(),
                ],

            ]
        );

    }
}
