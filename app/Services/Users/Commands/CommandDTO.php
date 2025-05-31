<?php

namespace App\Services\Users\Commands;

final readonly class CommandDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public int $id = 0,
    ) {
    }
} 