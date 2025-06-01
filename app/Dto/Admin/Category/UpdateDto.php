<?php

namespace App\Dto\Admin\Category;

class UpdateDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
    ) 
    {}
}