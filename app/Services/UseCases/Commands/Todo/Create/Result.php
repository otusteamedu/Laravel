<?php

namespace App\Services\UseCases\Commands\Todo\Create;

final readonly class Result
{
    /**
     * @param int $id
     */
    public function __construct(
        public int $id,
    ) {}
}
