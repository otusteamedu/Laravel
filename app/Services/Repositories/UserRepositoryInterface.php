<?php

namespace App\Services\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Получить спсисок всех пользователей
     * @return \App\Models\User[]
     */
    public function fetchAll(): array;

    /**
     * Получить пользователя по id
     * @param int $id
     * @return \App\Models\User|null
     */
    public function find(int $id): ?User;

    /**
     * Обновить пользователя
     * @param \App\Models\User $user
     * @return void
     */
    public function save(User $user): void;

    /**
     * Добавить пользователя
     * @param \App\Models\User $user
     * @return void
     */
    public function add(User $user): void;

    /**
     * Получить пользователя по email
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findByEmail(string $email): ?User;
}
