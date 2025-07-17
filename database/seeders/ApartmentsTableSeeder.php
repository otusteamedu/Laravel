<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  // <- Добавь эту строку

class ApartmentsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('apartments')->insert([
            [
                'owner' => 'Иван Иванов',
                'serial_number' => 1001,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner' => 'Мария Петрова',
                'serial_number' => 1002,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'owner' => 'Алексей Смирнов',
                'serial_number' => 1003,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
