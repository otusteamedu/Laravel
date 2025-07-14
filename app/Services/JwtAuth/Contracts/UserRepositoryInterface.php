<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Contracts;

use Tymon\JWTAuth\Contracts\JWTSubject;

interface UserRepositoryInterface
{
    public function find(int $id): ?JWTSubject;
}
