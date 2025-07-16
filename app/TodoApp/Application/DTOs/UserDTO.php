<?php

namespace App\TodoApp\Application\DTOs;

final readonly class UserDTO
{
    /**
     * @param int $userId
     * @param string $name
     * @param string $email
     * @param UserProfileDTO|null $profile
     */
    public function __construct(
        public int    $userId,
        public string $name,
        public string $email,
        public ?UserProfileDTO $profile = null
    ) {}
}
