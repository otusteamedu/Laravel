<?php

namespace App\Application\UseCases\Category\Queries\FetchCategoryById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
