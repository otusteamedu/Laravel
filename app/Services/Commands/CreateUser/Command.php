<?php

namespace App\Services\Commands\CreateUser;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $email,
        public bool $isAdmin = false,
        public ?string $password = null,
    ) {
    }
} 