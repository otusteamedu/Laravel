<?php

namespace App\Services\Repositories\DTOs;

use Carbon\Carbon;


final readonly class UserDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $email
     */
    public function __construct(
        public int    $id,
        public string $name,
        public string $email,
    ) {}
}
