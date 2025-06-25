<?php

namespace App\Services\UseCases\Commands\Auth\Password\Reset;

final readonly class Command
{
    public function __construct(
        public string $email,
        public ?bool $sendResetLink = true,
        public ?bool $forceReset = false,
    ) {}
}
