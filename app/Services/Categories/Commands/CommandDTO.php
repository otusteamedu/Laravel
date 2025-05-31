<?php

namespace App\Services\Categories\Commands;

final readonly class CommandDTO
{
    public function __construct(
        public string $name,
        public string $color,
        public string $description,
        public int $id = 0,
    ) {
    }

}
