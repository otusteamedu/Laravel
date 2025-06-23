<?php

namespace App\Services\Team;

use App\Models\Team;
use App\Repositories\TeamRepository;

class TeamsViewService
{
    public function __construct(
        private TeamRepository $teamRepository,
    )
    {
    }

    public function fetchOne(int $id): ?TeamData
    {
        $team = $this->teamRepository->one($id);
        if(is_null($team))
        {
            return null;
        }

        return new TeamData($team->toArray());
    }

    /**
     * @return array<int,TeamData>
     */
    public function fetchAll(): array
    {
        $teams = $this->teamRepository->all();
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
}
