<?php

namespace App\Domain\Repositories\User\DTO;

final readonly class UserProfileDTO
{
    /**
     * @param int $userId
     * @param string|null $biography
     * @param int|null $telegram_id
     * @param int|null $id
     */
    public function __construct(
        public int     $userId,
        public ?string $biography = null,
        public ?int   $telegram_id = null,
        public ?int   $id = null,
    ) {}
}
