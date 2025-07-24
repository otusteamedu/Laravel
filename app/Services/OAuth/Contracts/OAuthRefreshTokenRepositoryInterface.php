<?php

declare(strict_types=1);

namespace App\Services\OAuth\Contracts;

interface OAuthRefreshTokenRepositoryInterface
{
    public function revokeRefreshTokensByAccessTokenId(string $tokenId): void;
}
