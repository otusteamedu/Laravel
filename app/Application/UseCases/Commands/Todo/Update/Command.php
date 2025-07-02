<?php

namespace App\Application\UseCases\Commands\Todo\Update;

use Illuminate\Support\Carbon;

final readonly class Command
{
    /**
     * @param int $todoId
     * @param int $projectId
     * @param string $title
     * @param string $description
     * @param \Illuminate\Support\Carbon $deadline
     * @param int|null $authorId
     * @param int|null $statusId
     * @param array|null $options
     */
    public function __construct(
        public int $todoId,
        public int $projectId,
        public string $title,
        public string $description,
        public Carbon $deadline,
        public ?int $authorId = null,
        public ?int $statusId = null,
        public ?array $options = []
    ) {}
}
