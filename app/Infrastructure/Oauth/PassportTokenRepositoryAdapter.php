<?php

declare(strict_types=1);

namespace App\Infrastructure\Oauth;

use App\Services\OAuth\Contracts\OAuthTokenRepositoryInterface;
use Laravel\Passport\TokenRepository as PassportTokenRepository;

class PassportTokenRepositoryAdapter implements OAuthTokenRepositoryInterface
{
    public function __construct(private PassportTokenRepository $passportTokenRepository)
    {
    }

    public function revokeAccessToken(string $tokenId): void
    {
        $this->passportTokenRepository->revokeAccessToken($tokenId);
    }
}
