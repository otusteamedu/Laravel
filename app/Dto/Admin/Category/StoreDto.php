<?php

namespace App\Dto\Admin\Category;

class StoreDto
{
    public function __construct(
        public string $title,
        public ?string $description,
    ) 
    {}
}