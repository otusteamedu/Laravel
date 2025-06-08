<?php

namespace App\Services\Team;

readonly class TeamUpdateService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
    )
    {
    }

    public function handle(TeamData $data): void
    {
        $this->teamRepository->update($data);
    }
}
