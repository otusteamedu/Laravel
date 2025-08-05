<?php

namespace App\Domain\Task\Commands\ChangeTaskStatus;

use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\TaskStatus;

final readonly class Command
{
    public function __construct(
        public TaskId $taskId,
        public TaskStatus $status,
    ) {
    }
}