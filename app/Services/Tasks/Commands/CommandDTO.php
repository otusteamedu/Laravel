<?php

namespace App\Services\Tasks\Commands;

use Carbon\Carbon;

final readonly class CommandDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $executor_id,
        public int $category_id,
        public int $priority_id,
        public ?string $due_date = null,
        public int $id = 0,
    ) {
    }
} 