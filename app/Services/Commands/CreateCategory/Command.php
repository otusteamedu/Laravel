<?php

namespace App\Services\Commands\CreateCategory;

final readonly class Command
{
    public function __construct(
        public string $name,
        public string $color,
        public string $description,
    ) {
    }
} 