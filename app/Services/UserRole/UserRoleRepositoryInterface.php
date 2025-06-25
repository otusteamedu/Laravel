<?php

namespace App\Services\UserRole;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection;

interface UserRoleRepositoryInterface
{
    public function all(): Collection;

    public function one(int $id): ?UserRole;

    public function add(UserRole $userRole): void;

    public function destroy(UserRole $userRole): void;

    public function update(UserRole $userRole): void;
}
