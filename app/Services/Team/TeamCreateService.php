<?php

namespace App\Services\Team;

readonly class TeamCreateService
{

    public function __construct(
        private TeamRepositoryInterface $teamRepository,
    )
    {
    }

    public function handle(TeamData $teamData): void
    {
        $this->teamRepository->add($teamData);
    }
}
