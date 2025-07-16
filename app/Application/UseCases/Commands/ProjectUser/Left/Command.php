<?php

namespace App\Application\UseCases\Commands\ProjectUser\Left;

final readonly class Command
{
    /**
     * @param int $userId
     * @param int $projectId
     */
    public function __construct(
        public int   $userId,
        public int   $projectId,
    ) {}
}
