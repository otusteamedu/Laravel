<?php

namespace App\Services\TeamPlayer;

use Illuminate\Database\Eloquent\Collection;

interface PlayerRepositoryInterface
{
    public function allByTeam(int $teamId): Collection;

}
