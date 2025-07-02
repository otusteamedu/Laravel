<?php

namespace App\Services\UseCases\Queries\FetchCategoryById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
