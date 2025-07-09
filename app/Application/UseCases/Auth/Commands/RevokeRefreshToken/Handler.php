<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\RevokeRefreshToken;

use App\Domain\Auth\Repositories\RefreshTokenRepositoryInterface;
use App\Infrastructure\RefreshTokenHasher\RefreshTokenHasherInterface;

class Handler
{
    public function __construct(private RefreshTokenRepositoryInterface $refreshTokenRepository, private RefreshTokenHasherInterface $tokenHasher) {}

    public function handle(Command $command): bool
    {
        $hashedToken = $this->tokenHasher->hash($command->refreshToken);

        $token = $this->refreshTokenRepository->findByToken($hashedToken);

        if (!$token) {
            return false;
        }
        $token->revoked = true;

        return $this->refreshTokenRepository->save($token);
    }
}

