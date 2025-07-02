<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserSocialite;
use Illuminate\Support\Facades\Hash;
use App\Domain\Repositories\User\DTO\UserDTO;
use App\Domain\Repositories\User\DTO\UserCreateDTO;
use App\Domain\Repositories\User\DTO\UserProfileDTO;
use App\Domain\Repositories\Common\FetchOptions;
use App\Domain\Repositories\User\DTO\UserSocialiteDTO;
use App\Domain\Repositories\User\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Получить пользователей
     * @param FetchOptions $options
     * @return UserDTO[]|null
     */
    public function fetch(FetchOptions $options): ?array
    {
        $query = User::query();

        if (!empty($options->ids)) {
            $query->whereIn('id', $options->ids);
        }

        if (!empty($options->perPage)) {
            $query->limit($options->perPage);
        }

        if (!empty($options->page) && !empty($options->perPage)) {
            $query->offset(($options->page - 1) * $options->perPage);
        }

        $dbUsers = $query->get();

        if ($dbUsers === null) {
            return null;
        }

        return array_map(function ($dbUser) {
            return new UserDTO(
                userId: $dbUser['id'],
                name: $dbUser['name'],
                email: $dbUser['email'],
            );
        }, $dbUsers->toArray());
    }

    /**
     * Получить пользователя по id
     * @param int $id
     * @param bool|null $withProfile
     * @return UserDTO|null
     */
    public function find(int $id, $withProfile = false): ?UserDTO
    {
        $dbUser = User::query()
            ->where('id', $id)
            ->first();

        if ($dbUser === null) {
            return null;
        }

        if ($withProfile === true) {
            $profile = new UserProfileDTO(
                userId: $dbUser->id,
                biography: $dbUser->profile->biography,
                telegram_id: $dbUser->profile->telegram_id,
            );
        }

        return new UserDTO(
            userId: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
            profile: $profile ?? null
        );
    }

    /**
     * Добавить пользователя
     * @param UserCreateDTO $user
     * @return int
     */
    public function add(UserCreateDTO $user): int
    {
        $dbUser = User::create([
            'name'                  => $user->name,
            'email'                 => $user->email,
            'password'              => Hash::make($user->password),
            'email_verified_at'     => $user->email_verified_at
        ]);

        return $dbUser->refresh()->id;
    }

    /**
     * Обновить профиль пользователя
     * @param UserDTO $user
     * @return bool
     */
    public function save(UserDTO $user): bool
    {
        return User::query()
            ->where('id', $user->userId)
            ->update([
                'name'  => $user->name,
                'email' => $user->email,
            ]);
    }

    /**
     * Получить пользователя по email
     * @param string $email
     * @param bool $verified
     * @return UserDTO|null
     */
    public function findByEmail(string $email, bool $verified = false): ?UserDTO
    {
        $query = User::query()
            ->where('email', $email);

        if ($verified) {
            $query->whereNotNull('email_verified_at');
        }

        $dbUser = $query->first();

        if ($dbUser === null) {
            return null;
        }

        return new UserDTO(
            userId: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
        );
    }

    /**
     * Обновить или создать профиль пользователя
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function saveProfile(UserProfileDTO $userProfile): int
    {
        $profile = UserProfile::updateOrCreate(
            [
                'user_id' => $userProfile->userId,
            ],
            [
                'biography' => $userProfile->biography,
                'telegram_id' => $userProfile->telegram_id,
            ]
        );

        return $profile->refresh()->id;
    }

    /**
     * Обновить пароль пользователя
     * @param int $userIs
     * @param string $password
     * @return bool
     */
    public function passwordUpdate(int $userId, string $password): bool
    {
        return User::query()
            ->where('id', $userId)
            ->update([
                'password'  => Hash::make($password),
            ]);
    }

    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialiteId
     * @param string $driver
     * @return UserDTO|null
     */
    public function socialiteFind(string $id, string $driver): ?UserDTO
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
            userId: $dbUser->id,
            name: $dbUser->name,
            email: $dbUser->email,
        );
    }

    /**
     * Добавить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return int
     */
    public function socialiteAdd(UserSocialiteDTO $userSocialite): int
    {
        $dbData = UserSocialite::create([
            'user_id'      => $userSocialite->userId,
            'driver'       => $userSocialite->driver,
            'socialite_id' => $userSocialite->socialiteId,
        ]);

        return $dbData->refresh()->id;
    }

    /**
     * Обновить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return bool
     */
    public function socialiteSave(UserSocialiteDTO $userSocialite): bool
    {
        $processed = UserSocialite::query()
            ->where('id', $userSocialite->id)
            ->update([
                'user_id'      => $userSocialite->userId,
                'driver'       => $userSocialite->driver,
                'socialite_id' => $userSocialite->socialiteId,
            ]);

        return $processed ? true : false;
    }

    /**
     * Удалить привязку социальной сети у пользователя
     * @param int $is
     * @return bool
     */
    public function socialiteDestroy(int $id): bool
    {
        return UserSocialite::where('id', $id,)
            ->delete() ?? false;
    }
}
