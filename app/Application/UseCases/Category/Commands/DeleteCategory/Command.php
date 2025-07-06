<?php

namespace App\Application\UseCases\Category\Commands\DeleteCategory;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
