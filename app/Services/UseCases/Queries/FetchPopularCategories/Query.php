<?php

namespace App\Services\UseCases\Queries\FetchPopularCategories;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
    ) {
    }
}
