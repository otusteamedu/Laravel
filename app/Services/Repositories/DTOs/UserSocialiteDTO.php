<?php

namespace App\Services\Repositories\DTOs;

final readonly class UserSocialiteDTO
{
    /**
     * @param int $user_id
     * @param string $driver
     * @param string $socialite_id
     * @param int|null $id
     */
    public function __construct(
        public int     $user_id,
        public string $driver,
        public string $socialite_id,
        public ?int   $id = null,
    ) {}
}
