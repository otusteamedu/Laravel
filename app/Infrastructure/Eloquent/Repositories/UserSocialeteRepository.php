<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Models\UserSocialite;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserSocialiteDTO;
use App\Services\Repositories\UserSocialeteRepositoryInterface;

class UserSocialeteRepository implements UserSocialeteRepositoryInterface
{
    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialeteId
     * @param string $driver
     * @return UserDTO|null
     */
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

    /**
     * Добавить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return int
     */
    public function add(UserSocialiteDTO $userSocialite): int
    {
        $dbData = UserSocialite::create([
            'user_id'      => $userSocialite->user_id,
            'driver'       => $userSocialite->driver,
            'socialite_id' => $userSocialite->socialite_id,
        ]);

        return $dbData->refresh()->id;
    }

    /**
     * Обновить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return bool
     */
    public function save(UserSocialiteDTO $userSocialite): bool
    {
        $processed = UserSocialite::query()
            ->where('id', $userSocialite->id)
            ->update([
                'user_id'      => $userSocialite->user_id,
                'driver'       => $userSocialite->driver,
                'socialite_id' => $userSocialite->socialite_id,
            ]);

        return $processed ? true : false;
    }

    /**
     * Удалить привязку социальной сети у пользователя
     * @param int $is
     * @return bool
     */
    public function destroy(int $id): bool
    {
        return UserSocialite::where('id', $id,)
            ->delete() ?? false;
    }
}
