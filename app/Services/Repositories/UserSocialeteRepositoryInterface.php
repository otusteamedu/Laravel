<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserSocialeteDTO;

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
     * @param UserSocialeteDTO $userSocialite
     * @return int
     */
    public function add(UserSocialeteDTO $userSocialite): int;

    /**
     * Обновить привязку социальной сети к пользователю
     * @param UserSocialeteDTO $userSocialite
     * @return bool
     */
    public function save(UserSocialeteDTO $userSocialite): bool;

    /**
     * Удалить привязку социальной сети у пользователя
     * @param int $is
     * @return bool
     */
    public function destroy(int $id): bool;
}
