<?php

namespace App\Services\Repositories\DTOs;

final readonly class UserDTO
{
    /**
     * @param int $userId
     * @param string $name
     * @param string $email
     */
    public function __construct(
        public int    $userId,
        public string $name,
        public string $email,
    ) {}
}
