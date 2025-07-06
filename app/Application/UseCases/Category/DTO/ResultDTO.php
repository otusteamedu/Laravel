<?php

namespace App\Application\UseCases\Category\DTO;

final readonly class ResultDTO
{
    /**
     * @param CategoryDTO[] $items
     */
    public function __construct(
        public array $items
    ) {
    }
}
