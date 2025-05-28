<?php

namespace App\Services\Repositories;

final readonly class InsertTodoStatusesDTO
{
    /**
     * @param array TodoStatusDTO[]
     */
    public function __construct(
        public array $todoStatusDTOs,
    ) {}
}
