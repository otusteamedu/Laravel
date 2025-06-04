<?php

namespace App\Services\Commands\DeleteTask;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
} 