<?php

namespace App\Services\UseCases\Commands\CreateUser;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
    ) {
    }
}
