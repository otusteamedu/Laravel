<?php

namespace App\Application\UseCases\News\Commands\DeleteNews;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
