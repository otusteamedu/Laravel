<?php

namespace App\Services\Team;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{

    public function all(): Collection;

    public function one(int $id): ?Team;

    public function add(Team $team): void;

    public function destroy(Team $team): void;

    public function update(Team $team);
}
