<?php

namespace App\Services\Repositories;

use App\Services\Repositories\Common\FetchOptions;
use App\Services\Repositories\DTOs\UserDTO;
use App\Services\Repositories\DTOs\UserCreateDTO;
use App\Services\Repositories\DTOs\UserProfileDTO;

interface UserRepositoryInterface
{
    /**
     * Получить пользователей
     * @param FetchOptions $options
     * @return UserDTO[]|null
     */
    public function fetch(FetchOptions $options): ?array;

    /**
     * Получить пользователя по id
     * @param int $id
     * @param bool|null $withProfile
     * @return UserDTO|null
     */
    public function find(int $id, bool $withProfile = false): ?UserDTO;

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
     * @param bool $verified
     * @return UserDTO|null
     */
    public function findByEmail(string $email, bool $verified = false): ?UserDTO;

    /**
     * Обновить или создать профиль пользователя
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function saveProfile(UserProfileDTO $userProfile): int;

    /**
     * Обновить пароль пользователя
     * @param int $userId
     * @param string $password
     * @return bool
     */
    public function passwordUpdate(int $userId, string $password): bool;
}
