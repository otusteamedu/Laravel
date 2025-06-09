<?php

namespace App\Services\UseCases\Commands\Auth\Register;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
