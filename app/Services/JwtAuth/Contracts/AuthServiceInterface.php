<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Contracts;

use Tymon\JWTAuth\Contracts\JWTSubject;

interface AuthServiceInterface
{
    public function attempt(string $email, string $password): ?array;

    public function generateAccessToken(JWTSubject $user): string;

    public function getTtl(): int;
    public function getTokenType(): string;
}
