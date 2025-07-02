<?php

namespace App\Services\DTO\News;

final readonly class ResultDTO
{
    /**
     * @param NewsDTO[] $results
     */
    public function __construct(
        public array $results
    ) {
    }
}
