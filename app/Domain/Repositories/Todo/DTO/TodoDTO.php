<?php

namespace App\Domain\Repositories\Todo\DTO;

use Carbon\Carbon;

final readonly class TodoDTO
{
    /**
     * Summary of __construct
     * @param string $title
     * @param int $authorId
     * @param int $projectId
     * @param int $statusId
     * @param string $description
     * @param Carbon $deadline
     * @param Carbon|null $created
     * @param Carbon|null $updated
     * @param array|null $options
     * @param int|null $todoId
     */
    public function __construct(
        public string  $title,
        public int     $authorId,
        public int     $projectId,
        public int     $statusId,
        public string  $description,
        public Carbon  $deadline,
        public ?Carbon $created = null,
        public ?Carbon $updated = null,
        public ?array  $options = null,
        public ?int    $todoId = null,
    ) {}
}
