<?php

namespace App\Repositories;

use App\Models\Team;
use App\Services\Team\TeamData;
use App\Services\Team\TeamHasPlayersException;
use App\Services\Team\TeamRepositoryInterface;
use App\Services\TeamPlayer\PlayerRepositoryInterface;

class TeamRepository implements TeamRepositoryInterface
{
    public function add(TeamData $teamData): int
    {
        $team = Team::query()->create($teamData->toArray());
        return $team->id;
    }

    /**
     * @return array<int, TeamData>
     */
    public function all(): array
    {
        $teams = Team::all();
        $result = [];

        if($teams->isEmpty())
        {
            return $result;
        }

        foreach ($teams as $team) {
            $result[$team->id] = new TeamData($team->toArray());
        }

        return $result;
    }

    public function one(int $id): TeamData
    {
        $team = Team::query()->findOrFail($id);
        return new TeamData($team->toArray());
    }

    /**
     * @throws TeamHasPlayersException
     */
    public function destroy(int $id, PlayerRepositoryInterface $playerRepository): void
    {
        $team = Team::query()->findOrFail($id);
        $teamPlayers = $playerRepository->allByTeam($team->id);

        if(!empty($teamPlayers))
        {
            throw new TeamHasPlayersException('Команду нельзя удалить, сначала отвяжите игроков');
        }

        $team->delete();

    }

    public function update(TeamData $data): void
    {
        $team = Team::query()->findOrFail($data->getId());
        $team->update($data->toArray());
    }
}
