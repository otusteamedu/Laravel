<?php

namespace App\TodoApp\Application\UseCases\Commands\Project\Update;

final readonly class Result
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
