<?php

namespace App\Domain\Repositories\User\DTO;

use Carbon\Carbon;

final readonly class UserCreateDTO
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param Carbon|null $email_verified_at
     */
    public function __construct(
        public string  $name,
        public string  $email,
        public string  $password,
        public ?Carbon $email_verified_at = null
    ) {}
}
