<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Models\User;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserCreateDTO;
use App\Services\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Получить пользователя по id
     * @param int $id
     * @return UserDTO|null
     */
    public function find(int $id): ?UserDTO
    {
        $dbUser = User::query()
            ->where('id', $id)
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
     * Добавить пользователя
     * @param UserCreateDTO $user
     * @return int
     */
    public function add(UserCreateDTO $user): int
    {
        $dbUser = User::create([
            'name'              => $user->name,
            'email'             => $user->email,
            'password'          => $user->password,
            'email_verified_at' => $user->email_verified_at
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
            ->where('id', $user->id)
            ->update([
                'name'  => $user->name,
                'email' => $user->email,
            ]);
    }

    /**
     * Получить пользователя по email
     * @param string $email
     * @return UserDTO|null
     */
    public function findByEmail(string $email): ?UserDTO
    {
        $dbUser = User::query()
            ->where('email', $email)
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
}
