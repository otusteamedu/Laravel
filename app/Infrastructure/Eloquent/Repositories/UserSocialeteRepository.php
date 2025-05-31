<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Models\UserSocialite;
use App\Services\Repositories\UserDTO;
use App\Services\Repositories\UserSocialiteDTO;
use App\Services\Repositories\UserSocialeteRepositoryInterface;

class UserSocialeteRepository implements UserSocialeteRepositoryInterface
{
    public function find(string $id, string $driver): ?UserDTO
    {
        $dbUser = User::query()
            ->whereHas('socialites', function ($query) use ($id, $driver) {
                $query
                    ->where('driver', $driver)
                    ->where('socialite_id', $id);
            })
            ->first();

        if ($dbUser === null) {
            return null;
        }

        return new UserDTO(
            id: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
        );
    }

    public function add(UserSocialiteDTO $userSocialite): int
    {
        $dbData = UserSocialite::create([
            'user_id'      => $userSocialite->user_id,
            'driver'       => $userSocialite->driver,
            'socialite_id' => $userSocialite->socialite_id,
        ]);

        return $dbData->refresh()->id;
    }

    public function save(UserSocialiteDTO $userSocialite): bool
    {
        return UserSocialite::query()
            ->where('id', $userSocialite->id)
            ->update([
                'user_id'      => $userSocialite->user_id,
                'driver'       => $userSocialite->driver,
                'socialite_id' => $userSocialite->socialite_id,
            ]);
    }

    public function destroy(int $id): bool
    {
        return UserSocialite::where('id', $id,)
            ->delete() ?? false;
    }
}
