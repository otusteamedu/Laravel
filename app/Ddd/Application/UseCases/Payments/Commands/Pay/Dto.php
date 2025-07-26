<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Pay;

class Dto
{
    public function __construct(
        public string $uid,
        public int $amount
    ) 
    {}
}