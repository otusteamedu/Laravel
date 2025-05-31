<?php

namespace App\Services\Repositories;

use Carbon\Carbon;


final readonly class UserCreateDTO
{
    public function __construct(
        public string  $name,
        public string  $email,
        public string  $password = null,
        public ?Carbon $email_verified_at = null
    ) {}
}
