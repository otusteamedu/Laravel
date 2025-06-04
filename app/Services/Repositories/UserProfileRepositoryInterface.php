<?php

namespace App\Services\Repositories;

use App\Services\Repositories\DTOs\UserProfileDTO;

interface UserProfileRepositoryInterface
{
    /**
     * Получить профиль пользователя по его id
     * @param string $userId
     * @return UserProfileDTO|null
     */
    public function find(string $userId): ?UserProfileDTO;

    /**
     * Обновить или создать профиль пользователя
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function save(UserProfileDTO $userProfile): int;
}
