<?php
declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\RefreshToken;


use App\Models\RefreshToken;
use App\Services\JwtAuth\Contracts\RefreshTokenRepositoryInterface;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function findByToken(string $tokenHash): ?RefreshToken
    {
        return RefreshToken::query()->where('token', $tokenHash)->first();
    }

    public function deleteByToken(string $tokenHash): void
    {
        RefreshToken::query()->where('token', $tokenHash)->delete();
    }

    public function save(RefreshToken $refreshToken): bool
    {
        return $refreshToken->save();
    }
}
