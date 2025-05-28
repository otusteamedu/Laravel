<?php

namespace App\Dto\Category;

class StoreDto
{
    public function __construct(
        public string $title,
        public ?string $description,
    ) 
    {}
}