<?php

namespace App\Application\UseCases\User\Queries\FetchUserById;

final readonly class Query
{
    public function __construct(
        public int $id,
    ) {
    }
}
