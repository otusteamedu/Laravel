<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\RevokeRefreshToken;

class Command
{
    public function __construct(
        public string $refreshToken
    ) {}
}
