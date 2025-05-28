<?php

namespace Database\Seeders;

use App\Models\FantasyTeam;
use App\Models\TelegramUser;
use Illuminate\Database\Seeder;

class FantasyTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FantasyTeam::factory()->count(7)->create();
    }
}
