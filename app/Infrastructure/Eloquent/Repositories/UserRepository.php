<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Services\Repositories\UsersRepositoryInterface;

class UserRepository implements UsersRepositoryInterface
{
    public function fetchAll(): array
    {
        return User::all()->all();
    }

    public function find(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function save(User $User): void
    {
        $User->save();
    }

    public function add(User $User): void
    {
        $User->save();
    }
}
