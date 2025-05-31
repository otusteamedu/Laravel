<?php

namespace App\Services\Repositories;

use Carbon\Carbon;


final readonly class UserDTO
{
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
    ) {}
}
