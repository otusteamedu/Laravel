<?php

namespace App\Dto\Admin\Product;

class StoreDto
{
    public function __construct(
        public string $title,
        public ?string $description,
        public int $category_id,
        public int $price,
        public int $stock
    ) 
    {}
}