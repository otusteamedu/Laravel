<?php

namespace App\Services\UseCases\Commands\Auth\Password\Update;

final readonly class Command
{
    public function __construct(
        public int $userId,
        public string $password
    ) {}
}
