<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Services\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function fetchAll(): array
    {
        return User::all()->all();
    }

    public function find(int $id): ?User
    {
        return User::query()
            ->where('id', $id)
            ->first();
    }

    public function save(User $user): void
    {
        $user->save();
    }

    public function add(User $user): void
    {
        $user->save();
    }
    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->first();
    }
}
