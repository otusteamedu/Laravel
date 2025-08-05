<?php

namespace App\Domain\Task\Commands\DeleteTask;

use App\Domain\Task\ValueObjects\TaskId;

final readonly class Command
{
    public function __construct(
        public TaskId $id,
    ) {
    }
}