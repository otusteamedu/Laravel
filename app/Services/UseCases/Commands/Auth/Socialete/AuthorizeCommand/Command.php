<?php

namespace App\Services\UseCases\Commands\Auth\Socialete\AuthorizeCommand;

final readonly class Command
{
    public function __construct(
        public string $id,
        public string $driver,
        public string $email,
        public string $name,
        public ?bool  $remember = false,
    ) {}
}
