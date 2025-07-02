<?php

namespace App\Services\UseCases\Commands\DeleteNews;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
