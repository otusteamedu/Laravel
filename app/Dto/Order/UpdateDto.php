<?php

namespace App\Dto\Order;

class UpdateDto
{
    public function __construct(
        public int $id,
        public int $user_id
    ) 
    {}
}