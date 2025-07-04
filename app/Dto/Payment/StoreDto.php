<?php

namespace App\Dto\Payment;

class StoreDto
{
    public function __construct(
        public string $uid,
        public int $orderId,
        public string $status,
        public int $amount
    ) 
    {}
}