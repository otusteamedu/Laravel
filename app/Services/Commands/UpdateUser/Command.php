<?php

namespace App\Services\Commands\UpdateUser;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public bool $isAdmin = false,
        public ?string $password = null,
    ) {
    }
} 