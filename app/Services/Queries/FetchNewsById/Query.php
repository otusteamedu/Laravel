<?php

namespace App\Services\Queries\FetchNewsById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
