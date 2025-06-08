<?php

namespace App\Services\UseCases\Commands\ProjectUser\Join;

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
