<?php

namespace App\Services\Repositories;

final readonly class UserSocialiteDTO
{
    public function __construct(
        public int     $user_id,
        public string $driver,
        public string $socialite_id,
        public ?int   $id = null,
    ) {}
}
