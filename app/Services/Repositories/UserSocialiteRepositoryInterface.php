<?php

namespace App\Services\Repositories;

use App\TodoApp\Application\DTOs\UserDTO;
use App\TodoApp\Application\DTOs\UserSocialiteDTO;

interface UserSocialiteRepositoryInterface
{
    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialiteId
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
