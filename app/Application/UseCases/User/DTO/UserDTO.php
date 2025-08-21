<?php

namespace App\Application\UseCases\User\DTO;

use Carbon\Carbon;

final readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
        public ?\DateTimeInterface $emailVerifiedAt,
        public array $roles = [],
        //public array $permissions = [],
        public ?bool $subscribedNews = null,

    ) {
    }
}
