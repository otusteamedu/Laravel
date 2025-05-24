<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Models\UserSocialite;
use App\Services\Repositories\UserSocialeteRepositoryInterface;

class UserSocialeteRepository implements UserSocialeteRepositoryInterface
{
    public function find(string $id, string $driver): ?User
    {
        return User::query()
            ->whereHas('socialites', function ($query) use ($id, $driver) {
                $query
                    ->where('driver', $driver)
                    ->where('socialite_id', $id);
            })
            ->first();
    }

    public function add(UserSocialite $userSocialite): void
    {
        $userSocialite->save();
    }

    public function destroy(UserSocialite $userSocialite): void
    {
        $userSocialite->delete();
    }
}
