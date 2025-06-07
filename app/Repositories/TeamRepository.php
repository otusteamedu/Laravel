<?php

namespace App\Repositories;

use App\Models\Team;
use App\Services\Team\TeamData;

class TeamRepository implements TeamRepositoryInterface
{
    public function create(TeamData $data): int
    {
        $team = Team::query()->create($data->toArray());
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

    public function destroy(int $id): void
    {
        Team::query()->findOrFail($id)->delete();
    }

    public function update(TeamData $data): void
    {
        $team = Team::query()->findOrFail($data->getId());
        $team->update($data->toArray());
    }
}
