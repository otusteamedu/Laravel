<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserSocialiteDTO;

interface UserSocialeteRepositoryInterface
{
    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialeteId
     * @param string $driver
     * @return UserDTO|null
     */
    public function find(string $id, string $driver): ?UserDTO;

    /**
     * Добавить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return int
     */
    public function add(UserSocialiteDTO $userSocialite): int;

    /**
     * Обновить привязку социальной сети к пользователю
     * @param UserSocialiteDTO $userSocialite
     * @return bool
     */
    public function save(UserSocialiteDTO $userSocialite): bool;

    /**
     * Удалить привязку социальной сети у пользователя
     * @param int $is
     * @return bool
     */
    public function destroy(int $id): bool;
}
