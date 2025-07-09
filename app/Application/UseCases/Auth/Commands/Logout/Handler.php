<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\Logout;

use App\Domain\Auth\Repositories\RefreshTokenRepositoryInterface;
use App\Infrastructure\RefreshTokenHasher\RefreshTokenHasherInterface;
use Illuminate\Support\Facades\Auth;

class Handler
{
    public function __construct(private RefreshTokenRepositoryInterface $refreshTokenRepository, private RefreshTokenHasherInterface $tokenHasher) {}

    public function handle(Command $command): void
    {
        $hashedToken = $this->tokenHasher->hash($command->refreshToken);
        $this->refreshTokenRepository->deleteByToken($hashedToken);

        Auth::logout();
    }
}

