<?php

namespace App\Repositories;

use App\Models\Player;
use App\Services\TeamPlayer\PlayerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository implements PlayerRepositoryInterface
{

    public function allByTeam(int $teamId): Collection
    {
        return $teamPlayers = Player::query()->where("team_id", $teamId)->get();
    }
}
