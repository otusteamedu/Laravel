<?php

namespace App\Services\Commands\CreateTask;

final readonly class Command
{
    public function __construct(
        public string $title,
        public string $description,
        public int $executorId,
        public int $categoryId,
        public int $priorityId,
        public string $status = 'новая',
        public ?string $dueDate = null,
    ) {
    }
} 