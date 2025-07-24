<?php

declare(strict_types=1);

namespace App\Infrastructure\Oauth;

use App\Services\OAuth\Contracts\OAuthRefreshTokenRepositoryInterface;
use Laravel\Passport\RefreshTokenRepository as PassportRefreshTokenRepository;

class PassportRefreshTokenRepositoryAdapter implements OAuthRefreshTokenRepositoryInterface
{
    public function __construct(private PassportRefreshTokenRepository $passportRefreshTokenRepository)
    {
    }

    public function revokeRefreshTokensByAccessTokenId(string $tokenId): void
    {
        $this->passportRefreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }
}
