<?php

namespace App\Repositories;

use App\Models\User;
use App\Services\User\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{

    public function all(): Collection
    {
        return User::all();
    }

    public function one(int $id): ?User
    {
        return User::query()->find($id);
    }

    public function add(User $user): void
    {
        $user->save();
    }

    public function destroy(User $user): void
    {
        $user->delete();
    }

    public function update(User $user): void
    {
        $user->save();
    }

    public function oneByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
