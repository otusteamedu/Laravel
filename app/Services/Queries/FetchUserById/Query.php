<?php

namespace App\Services\Queries\FetchUserById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
} 