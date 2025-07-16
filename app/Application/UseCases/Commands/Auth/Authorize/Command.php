<?php

namespace App\Application\UseCases\Commands\Auth\Authorize;

final readonly class Command
{
    public function __construct(
        public string $email,
        public string $password,
        public ?bool  $remember = false,
    ) {}
}
