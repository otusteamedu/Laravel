<?php

namespace App\Services\UseCases\Queries\FetchUserById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
