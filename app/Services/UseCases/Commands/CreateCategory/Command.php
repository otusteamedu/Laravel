<?php

namespace App\Services\UseCases\Commands\CreateCategory;

final readonly class Command
{
    public function __construct(
        public string $name,
        public bool $isActive,
        public int $sort,
    ) {
    }
}
