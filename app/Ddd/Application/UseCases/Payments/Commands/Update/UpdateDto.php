<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Update;

class UpdateDto
{
    public function __construct(
        public string $uid,
        public string $status,
        public int $amount
    ) 
    {}
}