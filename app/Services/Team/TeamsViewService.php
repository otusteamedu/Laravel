<?php

namespace App\Services\Team;

use App\Repositories\TeamRepository;

readonly class TeamsViewService
{
    public function __construct(
        private TeamRepository $teamRepository,
    )
    {
    }

    public function fetchOne(int $id): TeamData
    {
        return $this->teamRepository->one($id);
    }

    /**
     * @return array<int,TeamData>
     */
    public function fetchAll(): array
    {
        return $this->teamRepository->all();
    }
}
