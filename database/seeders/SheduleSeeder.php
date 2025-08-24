<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shedules')->insert(
            [
                [
                    'language_code' => 1,
                    'group_code' => random_int(1, 3),
                    'date' => 'Ср,пт 18.00-19.00',
                    'teacher' => 'Новикова Н.Н.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language_code' => 1,
                    'group_code' => random_int(1, 3),
                    'date' => 'Вт,чт 18.00-19.00',
                    'teacher' => 'Новикова Н.Н.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language_code' => 2,
                    'group_code' => 1,
                    'date' => 'вт,чт 18.00-19.00',
                    'teacher' => 'Антонова А.А.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language_code' => 2,
                    'group_code' => random_int(2, 3),
                    'date' => 'вт,чт 19.00-20.00',
                    'teacher' => 'Антонова А.А.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language_code' => 3,
                    'group_code' => random_int(1, 3),
                    'date' => 'пн,сб 18.00-19.00',
                    'teacher' => 'Сюэ Шень',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language_code' => 3,
                    'group_code' => random_int(1, 3),
                    'date' => 'пн,сб 18.00-19.00',
                    'teacher' => 'Сюэ Шень',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
