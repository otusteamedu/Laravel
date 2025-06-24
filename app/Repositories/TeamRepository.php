<?php

namespace App\Repositories;

use App\Models\Team;
use App\Services\Team\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class TeamRepository implements TeamRepositoryInterface
{
    public function add(Team $team):void
    {
        $team->save();
    }

    public function all(): Collection
    {
        return Cache::rememberForever('teams' , function () {
            return Team::all();
        });
    }

    public function one(int $id): ?Team
    {
        $teams = Cache::rememberForever('teams' , function () {
            return Team::all();
        });

        return $teams->find($id);
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
