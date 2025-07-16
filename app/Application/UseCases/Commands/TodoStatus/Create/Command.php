<?php

namespace App\Application\UseCases\Commands\TodoStatus\Create;

final readonly class Command
{
    /**
     * @param int $projectId
     * @param string $name
     * @param int $sort
     * @param string $color
     */
    public function __construct(
        public int    $projectId,
        public string $name,
        public int    $sort,
        public string $color,
    ) {}
}
