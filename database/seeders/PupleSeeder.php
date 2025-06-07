<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PupleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('puples')->insert(
            [
                [
                    'name' => fake('ru_RU')->firstName,
                    'surname' => fake('ru_RU')->lastName,
                    'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'email' => fake()->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => fake('ru_RU')->firstName,
                    'surname' => fake('ru_RU')->lastName,
                    'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'email' => fake()->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => fake('ru_RU')->firstName,
                    'surname' => fake('ru_RU')->lastName,
                    'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'email' => fake()->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => fake('ru_RU')->firstName,
                    'surname' => fake('ru_RU')->lastName,
                    'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'email' => fake()->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => fake('ru_RU')->firstName,
                    'surname' => fake('ru_RU')->lastName,
                    'date_of_birth' => fake()->dateTimeBetween('2016-01-01', '2017-06-30')->format('d-m-Y'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'email' => fake()->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );

    }
}
