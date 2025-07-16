<?php

namespace App\Domain\Repositories\Todo\DTO;

final readonly class InsertTodoStatusesDTO
{
    /**
     * @param TodoStatusDTO[] $todoStatusDTOs
     */
    public function __construct(
        public array $todoStatusDTOs,
    ) {}
}
