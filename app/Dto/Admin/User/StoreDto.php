<?php

namespace App\Dto\Admin\User;

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