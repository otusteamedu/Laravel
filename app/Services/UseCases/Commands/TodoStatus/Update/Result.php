<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

final readonly class Result
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
