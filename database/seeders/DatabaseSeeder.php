<?php

namespace Database\Seeders;

use App\Models\FantasyTeam;
use App\Models\FantasyTeamPlayer;
use App\Models\Player;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserRoleSeeder::class,
            UserSeeder::class,
            TelegramUserSeeder::class,
            TeamSeeder::class,
            PlayerSeeder::class,
            FantasyTeamSeeder::class,
            FantasyTeamPlayerSeeder::class,
        ]);

        FantasyTeam::all()->each(function ($team) {
            $players = Player::inRandomOrder()->pluck('id')->take(30)->toArray(); // 30 случайных игроков

            FantasyTeamPlayer::factory()
                ->count(count($players))
                ->sequence(fn ($seq) => [
                    'player_id' => $players[$seq->index],
                    'fantasy_team_id' => $team->id,
                ])
                ->create();
        });
    }
}
