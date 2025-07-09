<?php

declare(strict_types=1);

namespace App\Infrastructure\Eloquent\Repositories\Auth;

use App\Domain\Auth\Repositories\RefreshTokenRepositoryInterface;
use App\Models\RefreshToken;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    public function create(array $data): RefreshToken
    {
        return RefreshToken::create($data);
    }

    public function findByToken(string $hashedToken): ?RefreshToken
    {
        return RefreshToken::where('token', $hashedToken)->first();
    }

    public function deleteByToken(string $hashedToken): void
    {
        RefreshToken::where('token', $hashedToken)->delete();
    }

    public function save(RefreshToken $token): bool
    {
        return $token->save();
    }
}
