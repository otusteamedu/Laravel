<?php

namespace App\Services\Repositories\DTOs;

final readonly class UserSocialiteDTO
{
    /**
     * @param int $userId
     * @param string $driver
     * @param string $socialiteId
     * @param int|null $id
     */
    public function __construct(
        public int     $userId,
        public string $driver,
        public string $socialiteId,
        public ?int   $id = null,
    ) {}
}
