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
                    'language' => 'английский',
                    'group' => 'младшая',
                    'date' => 'Ср,пт 18.00-19.00',
                    'teacher' => 'Новикова Н.Н.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language' => 'испанский',
                    'group' => 'младшая',
                    'date' => 'вт,чт 18.00-19.00',
                    'teacher' => 'Антонова А.А.',
                    'author_id' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'language' => 'китайский',
                    'group' => 'младшая',
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
