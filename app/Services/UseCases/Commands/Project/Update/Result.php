<?php

namespace App\Services\UseCases\Commands\Project\Update;

final readonly class Result
{
    public function __construct(
        public int $id,
    ) {}
}
