<?php

namespace App\Services\Team;

use App\Repositories\TeamRepository;

class TeamsViewService
{
    public function __construct(
        private readonly TeamRepository $teamRepository,
    )
    {
    }

    /**
     * @return array<int,TeamData>|TeamData
     */
    public function handle(?int $id): array|TeamData
    {
        if (is_null($id)) {
            return $this->teamRepository->all();
        }

        return $this->teamRepository->one($id);
    }
}
