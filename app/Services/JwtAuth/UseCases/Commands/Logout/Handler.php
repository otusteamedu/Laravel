<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Logout;

use App\Services\JwtAuth\Contracts\RefreshTokenHasherInterface;
use App\Services\JwtAuth\Contracts\RefreshTokenRepositoryInterface;

class Handler
{
    public function __construct(
        private RefreshTokenRepositoryInterface $refreshTokenRepository,
        private RefreshTokenHasherInterface $tokenHasher
    ) {}

    public function handle(Command $command): void
    {
        $hashedToken = $this->tokenHasher->hash($command->refreshToken);
        $this->refreshTokenRepository->deleteByToken($hashedToken);
    }
}
