<?php

namespace App\Application\UseCases\News\Queries\FetchNewsById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
