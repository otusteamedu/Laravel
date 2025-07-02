<?php

namespace App\Services\UseCases\Queries\FetchLatestNews;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
    ) {
    }
}
