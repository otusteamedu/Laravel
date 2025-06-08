<?php

namespace App\Services\TeamPlayer;

interface PlayerRepositoryInterface
{
    /**
     * @return array<int, PlayerData>
     */
    public function allByTeam(int $teamId): array;

}
