<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Logout;

final readonly class Command
{
    public function __construct(
        public string $refreshToken
    ) {}
}
