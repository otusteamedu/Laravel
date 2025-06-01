<?php

namespace App\Dto\User;

class ProfileDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) 
    {}
}