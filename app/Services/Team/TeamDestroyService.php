<?php

namespace App\Services\Team;

use App\Services\TeamPlayer\PlayerRepositoryInterface;

readonly class TeamDestroyService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
        private PlayerRepositoryInterface $playerRepository,
    )
    {
    }

    public function handle(int $id): void
    {
        $this->teamRepository->destroy($id, $this->playerRepository);
    }
}
