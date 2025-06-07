<?php

namespace App\Services\Repositories\DTOs;

final readonly class UserProfileDTO
{
    /**
     * @param int $userId
     * @param string|null $biography
     * @param int|null $id
     */
    public function __construct(
        public int     $userId,
        public ?string $biography = null,
        public ?int   $id = null,
    ) {}
}
