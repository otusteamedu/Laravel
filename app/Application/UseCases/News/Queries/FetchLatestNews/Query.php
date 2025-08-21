<?php

namespace App\Application\UseCases\News\Queries\FetchLatestNews;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
    ) {
    }
}
