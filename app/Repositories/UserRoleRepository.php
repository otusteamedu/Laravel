<?php

namespace App\Repositories;

use App\Models\UserRole;
use App\Services\UserRole\UserRoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRoleRepository implements UserRoleRepositoryInterface
{

    public function all(): Collection
    {
        return UserRole::all();
    }

    public function one(int $id): ?UserRole
    {
        return UserRole::query()->find($id);
    }

    public function add(UserRole $userRole): void
    {
        $userRole->save();
    }

    public function destroy(UserRole $userRole): void
    {
        $userRole->delete();
    }

    public function update(UserRole $userRole): void
    {
        $userRole->save();
    }
}
