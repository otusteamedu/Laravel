<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\Logout;

class Command
{
    public function __construct(
        public string $refreshToken
    ) {}
}
