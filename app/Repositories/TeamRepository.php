<?php

namespace App\Repositories;

use App\Models\Team;
use App\Services\Team\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    public function add(Team $team):void
    {
        $team->save();
    }

    public function all(): Collection
    {
        return Team::all();

    }

    public function one(int $id): ?Team
    {
        return Team::query()->find($id);
    }

    public function destroy(Team $team): void
    {
        $team->delete();
    }

    public function update($team): void
    {
        $team->save();
    }
}
