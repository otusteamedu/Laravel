<?php
declare(strict_types=1);

namespace App\Services\Category\Results;

final readonly class CategoriesDTO
{
    /**
     * @param CategoryDTO[] $results
     */
    public function __construct(
        public array $results,
    )
    {
    }
}
