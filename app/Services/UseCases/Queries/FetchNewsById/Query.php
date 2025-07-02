<?php

namespace App\Services\UseCases\Queries\FetchNewsById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
