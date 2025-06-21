<?php

namespace App\Services\UseCases\Commands\Todo\Create;

use Illuminate\Support\Carbon;

final readonly class Command
{
    /**
     * @param string $title
     * @param int $authorId
     * @param int $projectId
     * @param int $statusId
     * @param string $description
     * @param \Illuminate\Support\Carbon $deadline
     * @param array|null $options
     */
    public function __construct(
        public string $title,
        public int $authorId,
        public int $projectId,
        public int $statusId,
        public string $description,
        public Carbon $deadline,
        public ?array $options = []
    ) {}
}
