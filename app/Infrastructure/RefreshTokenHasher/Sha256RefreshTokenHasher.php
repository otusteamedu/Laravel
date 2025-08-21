<?php

declare(strict_types=1);

namespace App\Infrastructure\RefreshTokenHasher;

use App\Services\JwtAuth\Contracts\RefreshTokenHasherInterface;

class Sha256RefreshTokenHasher implements RefreshTokenHasherInterface
{
    public function hash(string $token): string
    {
        return hash('sha256', $token);
    }

    public function verify(string $token, string $hashedToken): bool
    {
        return hash_equals($this->hash($token), $hashedToken);
    }
}
