<?php

namespace App\Services\UseCases\Commands\Project\Delete;

final readonly class Command
{
    public function __construct(
        public int $id,
    ) {}
}
