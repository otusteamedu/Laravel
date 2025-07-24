<?php

declare(strict_types=1);

namespace App\Services\OAuth\Contracts;

interface OAuthTokenRepositoryInterface
{
    public function revokeAccessToken(string $tokenId): void;
}
