<?php

namespace App\Services\Queries\FetchLatestNews;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
    ) {
    }
}
