<?php

namespace App\Services\Commands\DeleteNews;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
