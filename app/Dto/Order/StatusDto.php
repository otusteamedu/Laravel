<?php

namespace App\Dto\Order;

class StatusDto
{
    public function __construct(
        public int $id,
        public string $status
    ) 
    {}
}