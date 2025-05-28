<?php

namespace App\Dto\Category;

class UpdateDto
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
    ) 
    {}
}