<?php

declare(strict_types=1);

namespace App\Services\Commands\Auth\Socialete\AuthorizeCommand;

final readonly class Command
{
    public function __construct(
        public string $id,
        public string $driver,
        public string $email,
        public string $name,
    ) {}
}
