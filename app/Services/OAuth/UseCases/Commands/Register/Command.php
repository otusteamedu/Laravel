<?php

declare(strict_types=1);

namespace App\Services\OAuth\UseCases\Commands\Register;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}
}
