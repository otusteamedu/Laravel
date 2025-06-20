<?php

namespace App\Services\DTO\Tasks;

use Carbon\Carbon;

final readonly class TaskDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public int $executorId,
        public string $executorName,
        public int $categoryId,
        public string $categoryName,
        public string $categoryColor,
        public int $priorityId,
        public string $priorityName,
        public string $status,
        public ?Carbon $dueDate,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }
} 