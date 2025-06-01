<?php

namespace App\Dto\User;

class PasswordDto
{
    public function __construct(
        public int $id,
        public string $password_hash
    ) 
    {}
}