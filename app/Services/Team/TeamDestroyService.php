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

    /**
     * @throws TeamNotFoundException
     * @throws TeamHasPlayersException
     */
    public function handle(int $id): void
    {
        $team = $this->teamRepository->one($id);
        if(empty($team))
        {
            throw new TeamNotFoundException();
        }

        $teamPlayers = $this->playerRepository->allByTeam($team->id);

        if(!$teamPlayers->isEmpty())
        {
            throw new TeamHasPlayersException('Команду нельзя удалить, сначала отвяжите игроков');
        }

        $this->teamRepository->destroy($team);
    }
}
