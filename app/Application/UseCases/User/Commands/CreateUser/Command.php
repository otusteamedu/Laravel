<?php

namespace App\Application\UseCases\User\Commands\CreateUser;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $password = null,
        public array $roles = [],        // например: ['user']
        //public array $permissions = [],  // например: []
    ) {
    }
}
