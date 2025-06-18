<?php

namespace App\Services\Team;

use App\Models\Team;

class TeamCreateService
{

    public function __construct(
        private TeamRepositoryInterface $teamRepository,
    )
    {
    }

    public function handle(TeamData $teamData): void
    {
        $this->teamRepository->add(new Team($teamData->toArray()));
    }
}
