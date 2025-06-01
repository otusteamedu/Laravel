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
     * Добавить запись пользователю
     * @param UserProfileDTO $userProfile
     * @return int
     */
    public function add(UserProfileDTO $userProfile): int
    {
        $dbData = UserSocialite::create([
            'user_id'   => $userProfile->user_id,
            'biography' => $userProfile->biography,
        ]);

        return $dbData->refresh()->id;
    }

    /**
     * Обновить ппрофиль пользователя
     * @param UserProfileDTO $userProfile
     * @return bool
     */
    public function save(UserProfileDTO $userProfile): bool
    {
        return UserSocialite::query()
            ->where('user_id', $userProfile->user_id)
            ->update([
                'biography' => $userProfile->biography,
            ]);
    }
}
