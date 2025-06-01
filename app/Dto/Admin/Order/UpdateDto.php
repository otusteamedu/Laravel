<?php

namespace App\Dto\Admin\Order;

class UpdateDto
{
    public function __construct(
        public int $id,
        public int $user_id
    ) 
    {}
}