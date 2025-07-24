<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\OAuthUsers;

use App\Models\User;
use App\Services\OAuth\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param User $user
     *
     * @return bool
     */
    public function save(User $user): bool {
        return $user->save();
    }
}
