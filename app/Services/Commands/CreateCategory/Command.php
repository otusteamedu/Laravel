<?php

namespace App\Services\Commands\CreateCategory;

final readonly class Command
{
    public function __construct(
        public string $name,
        public int $sort,
    ) {
    }
}
