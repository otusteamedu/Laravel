<?php

namespace App\Services\Repositories\DTOs;

final readonly class InsertTodoStatusesDTO
{
    /**
     * @param TodoStatusDTO[] $todoStatusDTOs
     */
    public function __construct(
        public array $todoStatusDTOs,
    ) {}
}
