<?php

namespace App\Services\Repositories;

use App\Models\UserProfile;
use App\Models\UserSocialite;
use App\Services\Repositories\DTOs\UserProfileDTO;

class UserProfileRepository
{
    /**
     * Получить профиль пользователя по его id
     * @param string $userId
     * @return UserProfileDTO|null
     */
    public function find(string $userId): ?UserProfileDTO
    {
        $dbUserProfile = UserProfile::query()
            ->where('user_id', $userId)
            ->first();

        if ($dbUserProfile === null) {
            return null;
        }

        return new UserProfileDTO(
            id: $dbUserProfile->id,
            user_id: $dbUserProfile->user_id,
            biography: $dbUserProfile->biography,
        );
    }

    /**
     * Обновить или создать профиль пользователя
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function save(UserProfileDTO $userProfile): int
    {
        $profile = UserProfile::updateOrCreate(
            [
                'user_id' => $userProfile->user_id,
            ],
            [
                'biography' => $userProfile->biography,
            ]
        );

        return $profile->refresh()->id;
    }
}
