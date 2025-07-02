<?php

namespace App\Services\UseCases\Queries\FetchAllNews;

final readonly class Query
{
    public function __construct(
        public int $limit = 100,
        public int $offset = 0,
    ) {
    }
}
