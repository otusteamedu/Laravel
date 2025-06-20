<?php

namespace App\Services\DTO\Users;

use Carbon\Carbon;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public bool $isAdmin,
        public Carbon $createdAt,
        public Carbon $updatedAt,
        public ?Carbon $emailVerifiedAt,
    ) {
    }
} 