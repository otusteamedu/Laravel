<?php

namespace App\Dto\Order;

class StoreDto
{
    public function __construct(
        public int $user_id
    ) 
    {}
}