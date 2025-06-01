<?php

namespace App\Services\UseCases\Commands\TodoStatus\Create;

final readonly class Command
{
    /**
     * @param int $project_id
     * @param string $name
     * @param int $sort
     * @param string $color
     */
    public function __construct(
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
