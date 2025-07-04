<?php

namespace App\Dto\Payment;

class UpdateDto
{
    public function __construct(
        public string $uid,
        public string $status,
        public int $amount
    ) 
    {}
}