<?php

namespace App\Services\UseCases\Commands\DeleteCategory;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {
    }
}
