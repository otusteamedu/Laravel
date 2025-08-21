<?php

declare(strict_types=1);

namespace App\Application\UseCases\News\DTO;

/**
 * DTO для списка новостей без пагинации
 */
final readonly class ResultDTO
{
    /**
     * @param NewsDTO[] $items
     */
    public function __construct(
        public array $items
    ) {
    }
}

