<?php

namespace App\Services\Repositories;

use App\Models\User;
use App\Models\UserSocialite;

interface UserSocialeteRepositoryInterface
{

    /**
     * Получить пользователя по его id в социальной сети
     * @param string $socialeteId
     * @param string $driver
     * @return \App\Models\User|null
     */
    public function find(string $socialeteId, string $driver): ?User;

    /**
     * Добавить привязку социальной сети к пользователю
     * @param \App\Models\UserSocialite $userSocialite
     * @return void
     */
    public function add(UserSocialite $userSocialite): void;

    /**
     * Обновить привязку социальной сети к пользователю
     * @param \App\Models\UserSocialite $userSocialite
     * @return void
     */
    public function save(UserSocialite $userSocialite): void;

    /**
     * Удалить привязку социальной сети у пользователя
     * @param \App\Models\UserSocialite $userSocialite
     * @return void
     */
    public function destroy(UserSocialite $userSocialite): void;
}
