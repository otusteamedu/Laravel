<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\JwtAuthUsers;

use App\Models\User;
use App\Services\JwtAuth\Contracts\UserRepositoryInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param int $id
     *
     * @return JWTSubject|null
     */
    public function find(int $id): ?JWTSubject
    {
        return User::query()->find($id);
    }
}
