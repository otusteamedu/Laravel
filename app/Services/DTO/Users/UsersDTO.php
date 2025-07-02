<?php

namespace App\Services\DTO\Users;

final readonly class UsersDTO
{
    /**
     * @param UserDTO[] $results
     */
    public function __construct(
        public array $results
    ) {
    }
}
