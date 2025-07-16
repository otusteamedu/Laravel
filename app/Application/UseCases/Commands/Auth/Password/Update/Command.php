<?php

namespace App\Application\UseCases\Commands\Auth\Password\Update;

final readonly class Command
{
    public function __construct(
        public int $userId,
        public string $password
    ) {}
}
