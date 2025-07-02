<?php

namespace App\Services\Commands\DeleteCategory;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
} 