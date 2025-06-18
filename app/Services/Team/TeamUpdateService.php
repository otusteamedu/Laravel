<?php

namespace App\Services\Team;

use App\Models\Team;

class TeamUpdateService
{
    public function __construct(
        private TeamRepositoryInterface $teamRepository,
    )
    {
    }

    /**
     * @throws TeamNotFoundException
     */
    public function handle(TeamData $teamData): ?string
    {

        $team = $this->teamRepository->one($teamData->id);
        if(empty($team))
        {
            throw new TeamNotFoundException();
        }
        $oldLogoPath = $team->logo_path;
        $team->fill($teamData->toArray());
        $this->teamRepository->update($team);

        return $oldLogoPath;
    }
}
