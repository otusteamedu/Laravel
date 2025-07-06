<?php

namespace App\Application\UseCases\Category\Queries\FetchPopularCategories;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
    ) {
    }
}
