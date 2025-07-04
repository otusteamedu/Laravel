<?php

namespace App\Dto\User;

class PaymentDto
{
    public function __construct(
        public string $uid,
        public int $orderId,
        public string $status,
        public string $amount
    ) 
    {}
}