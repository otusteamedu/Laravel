<?php

namespace App\Dto\Search;

class SearchDto
{
    public function __construct(
        public string $q,
        public string $category_id,
        public string $min_price,
        public string $max_price,
        public array $brands,
        public string $rating,
        public string $min_screen,
        public string $max_screen,
        public string $min_ram,
        public string $max_ram,
        public string $min_builtin,
        public string $max_builtin
    ) 
    {}
}