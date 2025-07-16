<?php

namespace App\TodoApp\Application\UseCases\Commands\TodoStatus\Create;

final readonly class Result
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
