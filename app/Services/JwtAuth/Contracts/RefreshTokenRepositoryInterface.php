<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\Contracts;

use App\Models\RefreshToken;

interface RefreshTokenRepositoryInterface
{
    public function findByToken(string $tokenHash): ?RefreshToken;

    public function deleteByToken(string $tokenHash): void;

    public function save(RefreshToken $refreshToken): bool;
}
