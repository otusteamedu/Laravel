<?php

namespace App\Services\Team;

use App\Repositories\TeamRepositoryInterface;

class TeamDestroyService
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository
    )
    {
    }

    public function handle(int $id): void
    {
        $this->teamRepository->destroy($id);
    }
}
