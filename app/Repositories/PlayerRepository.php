<?php

namespace App\Repositories;

use App\Models\Player;
use App\Services\TeamPlayer\PlayerData;
use App\Services\TeamPlayer\PlayerRepositoryInterface;

class PlayerRepository implements PlayerRepositoryInterface
{

    /**
     * @return array<int, PlayerData>
     */
    public function allByTeam(int $teamId): array
    {
        $teamPlayers = Player::query()->where("team_id", $teamId)->get();
        $result = [];
        if($teamPlayers->isEmpty()){
            return $result;
        }

        foreach ($teamPlayers as $player) {
            $result[$player->id] = new PlayerData($player->toArray());
        }

        return $result;
    }
}
