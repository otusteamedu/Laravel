<?php
declare(strict_types=1);

namespace App\Services\Category\Results;

final readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public int $sort,
    )
    {
    }
}
