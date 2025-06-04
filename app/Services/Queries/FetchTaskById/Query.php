<?php

namespace App\Services\Queries\FetchTaskById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
} 