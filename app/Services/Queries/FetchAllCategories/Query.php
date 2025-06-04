<?php

namespace App\Services\Queries\FetchAllCategories;

final readonly class Query
{
    public function __construct(
        public int $perPage = 10,
    ) {
    }
} 