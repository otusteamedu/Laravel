<?php

namespace App\Dto\Admin\Product;

class UpdateDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public int $category_id,
        public int $price,
        public int $stock
    ) 
    {}
}