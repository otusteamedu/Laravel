<?php

declare(strict_types=1);

namespace App\Services\JwtAuth\UseCases\Commands\Login;

final readonly class Command
{
    public function __construct(
        public string $email,
        public string $password,
        public int $refreshTtl = 20160, // minutes
    ) {}
}
