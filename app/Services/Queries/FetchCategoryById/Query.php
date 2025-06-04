<?php

namespace App\Services\Queries\FetchCategoryById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
} 