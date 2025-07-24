<?php

declare(strict_types=1);

namespace App\Services\OAuth\UseCases\Commands\Logout;

use App\Services\OAuth\Contracts\OAuthTokenRepositoryInterface;
use App\Services\OAuth\Contracts\OAuthRefreshTokenRepositoryInterface;

class Handler
{
    public function __construct(
        private OAuthTokenRepositoryInterface $tokenRepository,
        private OAuthRefreshTokenRepositoryInterface $refreshTokenRepository
    )
    {
    }

    public function handle(Command $command): void
    {
        $this->tokenRepository->revokeAccessToken($command->tokenId);
        $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($command->tokenId);
    }
}
