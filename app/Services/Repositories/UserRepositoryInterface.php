<?php

namespace App\Services\Repositories;

use App\Models\User;

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
     * Получить пользователя по email
     * @param string $email
     * @return UserDTO|null
     */
    public function findByEmail(string $email): ?UserDTO;

    /**
     * Авторизовать пользователя по id
     * @param int   $id
     * @param ?bool $remeber
     * @return void
     */
    public function login(int $id, bool $remember = false): void;

    /**
     * Разлогинить текущего пользователя
     * @return void
     */
    public function logout(): void;
}
