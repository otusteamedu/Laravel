<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\User;
use App\Models\UserSocialite;

interface UserSocialeteRepositoryInterface
{
    /**
     * @return User
     */
    public function find(string $socialeteId, string $driver): ?User;

    public function save(UserSocialite $userSocialite): void;

    public function add(UserSocialite $userSocialite): void;
}
