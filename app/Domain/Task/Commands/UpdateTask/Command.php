<?php

namespace App\Domain\Task\Commands\UpdateTask;

use App\Domain\Task\ValueObjects\CategoryId;
use App\Domain\Task\ValueObjects\PriorityId;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskDueDate;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Domain\Task\ValueObjects\UserId;

final readonly class Command
{
    public function __construct(
        public TaskId $id,
        public TaskTitle $title,
        public TaskDescription $description,
        public UserId $executorId,
        public CategoryId $categoryId,
        public PriorityId $priorityId,
        public ?TaskDueDate $dueDate = null,
    ) {
    }
}