<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Refresh;

final readonly class Command
{
    public function __construct(
        public string $refreshToken,
        public int $refreshTtl = 20160, // minutes
    ) {}
}
