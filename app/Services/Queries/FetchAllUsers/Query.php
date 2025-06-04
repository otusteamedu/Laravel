<?php

namespace App\Services\Queries\FetchAllUsers;

final readonly class Query
{
    public function __construct(
        public int $perPage = 10,
    ) {
    }
} 