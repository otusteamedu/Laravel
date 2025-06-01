<?php

namespace App\Dto\Admin\User;

class UpdateDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public int $role_id
    ) 
    {}
}