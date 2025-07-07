<?php

namespace App\TodoApp\Application\DTOs;

use DateTime;

final readonly class UserCreateDTO
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param DateTime|null $email_verified_at
     */
    public function __construct(
        public string  $name,
        public string  $email,
        public string  $password,
        public ?DateTime $email_verified_at = null
    ) {}
}
