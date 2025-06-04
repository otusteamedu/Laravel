<?php

namespace App\Services\Commands\UpdateCategory;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color,
        public string $description,
    ) {
    }
} 