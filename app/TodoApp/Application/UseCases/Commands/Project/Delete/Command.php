<?php

namespace App\TodoApp\Application\UseCases\Commands\Project\Delete;

final readonly class Command
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
