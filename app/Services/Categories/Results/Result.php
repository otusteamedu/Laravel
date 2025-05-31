<?php

namespace App\Services\Categories\Results;
final readonly class Result {
    /**
     * @param CategoryDTO[] $results
     */
    public function __construct(
        public array $results
    ) {}
}
