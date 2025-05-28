<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;

class Result
{
    /**
     * @param ProjectDTO
     * @param array TodoStatusDTO[]
     */
    public function __construct(
        public ProjectDTO $ptojectDTO,
        public array $todoStatusDTOs,
    ) {}
}
