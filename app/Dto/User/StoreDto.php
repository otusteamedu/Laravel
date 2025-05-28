<?php

namespace App\Dto\User;

class StoreDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password_hash,
        public int $role_id
    ) 
    {}
}