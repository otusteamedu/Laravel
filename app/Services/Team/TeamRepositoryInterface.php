<?php

namespace App\Services\Team;

use App\Services\TeamPlayer\PlayerRepositoryInterface;

interface TeamRepositoryInterface
{
    /**
     * @return array<int, TeamData>
     */
    public function all(): array;

    public function one(int $id): TeamData;

    public function add(TeamData $teamData): int;

    public function destroy(int $id, PlayerRepositoryInterface $playerRepository): void;

    public function update(TeamData $data);
}
