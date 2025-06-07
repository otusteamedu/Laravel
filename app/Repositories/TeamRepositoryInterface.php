<?php

namespace App\Repositories;

use App\Services\Team\TeamData;

interface TeamRepositoryInterface
{
    /**
     * @return array<int, TeamData>
     */
    public function all(): array;

    public function one(int $id): TeamData;

    public function create(TeamData $data): int;

    public function destroy(int $id): void;

    public function update(TeamData $data);
}
