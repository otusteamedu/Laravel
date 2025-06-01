<?php

namespace App\Services\Repositories\DTOs;

final readonly class UserProfileDTO
{
    /**
     * @param int $user_id
     * @param string|null $biography
     * @param int|null $id
     */
    public function __construct(
        public int     $user_id,
        public ?string $biography = null,
        public ?int   $id = null,
    ) {}
}
