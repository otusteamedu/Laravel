<?php

namespace Database\Seeders;

use App\Models\TelegramUser;
use Illuminate\Database\Seeder;

class TelegramUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TelegramUser::factory()->count(10)->create();
    }
}
