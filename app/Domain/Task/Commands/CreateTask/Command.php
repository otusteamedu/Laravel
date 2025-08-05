<?php

namespace App\Domain\Task\Commands\CreateTask;

use App\Domain\Task\ValueObjects\CategoryId;
use App\Domain\Task\ValueObjects\PriorityId;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskDueDate;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Domain\Task\ValueObjects\UserId;

final readonly class Command
{
    public function __construct(
        public TaskTitle $title,
        public TaskDescription $description,
        public UserId $executorId,
        public CategoryId $categoryId,
        public PriorityId $priorityId,
        public UserId $creatorId,
        public ?TaskDueDate $dueDate = null,
    ) {
    }
}