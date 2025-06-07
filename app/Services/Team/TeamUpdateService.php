<?php

namespace App\Services\Team;

use App\Repositories\TeamRepositoryInterface;

class TeamUpdateService
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
    )
    {
    }

    public function handle(TeamData $data): void
    {
        $this->teamRepository->update($data);
    }
}
