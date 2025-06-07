<?php

namespace App\Services\UseCases\Commands\TodoStatus\Update;

final readonly class Command
{
    /**
     * @param int $ststusId
     * @param int $projectId
     * @param string $name
     * @param int $sort
     * @param string $color
     */
    public function __construct(
        public int    $statusId,
        public int    $projectId,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
