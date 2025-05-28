<?php

namespace App\Services\UseCases\Commands\Project\Create;

final readonly class Result
{
    public function __construct(
        public int $id,
    ) {}
}
