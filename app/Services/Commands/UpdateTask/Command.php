<?php

namespace App\Services\Commands\UpdateTask;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $executorId,
        public int $categoryId,
        public int $priorityId,
        public int $creatorId,
        public string $status,
        public ?string $dueDate = null,
    ) {
    }
} 