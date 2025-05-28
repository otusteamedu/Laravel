<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

final readonly class Result
{
    public function __construct(
        public int $id,
    ) {}
}
