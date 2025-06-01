<?php

namespace App\Dto\Admin\Order;

class StoreDto
{
    public function __construct(
        public int $user_id
    ) 
    {}
}