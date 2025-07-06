<?php

namespace App\Application\UseCases\Category\Commands\UpdateCategory;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $isActive,
        public int $sort,
    ) {
    }
}
