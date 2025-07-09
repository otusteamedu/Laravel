<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth\Commands\Login;

class Command
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
