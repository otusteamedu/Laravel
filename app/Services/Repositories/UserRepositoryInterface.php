<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserCreateDTO;

interface UserRepositoryInterface
{
    /**
     * Получить пользователя по id
     * @param int $id
     * @return UserDTO|null
     */
    public function find(int $id): ?UserDTO;

    /**
     * Добавить пользователя
     * @param UserCreateDTO $user
     * @return int
     */
    public function add(UserCreateDTO $user): int;

    /**
     * Обновить профиль пользователя
     * @param UserDTO $user
     * @return bool
     */
    public function save(UserDTO $user): bool;

    /**
     * Получить пользователя по email
     * @param string $email
     * @return UserDTO|null
     */
    public function findByEmail(string $email): ?UserDTO;
}
