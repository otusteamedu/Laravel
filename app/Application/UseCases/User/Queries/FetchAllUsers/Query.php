<?php

namespace App\Application\UseCases\User\Queries\FetchAllUsers;

final readonly class Query
{
    public function __construct(
        public int $limit = 10,
        public int $offset = 0,
    ) {
    }
}
