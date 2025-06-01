<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

final readonly class Command
{
    /**
     * @param int $id
     * @param int $project_id
     * @param string $name
     * @param int $sort
     * @param string $color
     */
    public function __construct(
        public int    $id,
        public int    $project_id,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
