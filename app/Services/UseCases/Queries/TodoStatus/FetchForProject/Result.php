<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

class Result
{
    /**
     * @param ProjectDTO $projectDTO
     * @param array TodoStatusDTO[]
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public array $todostatusDTOs,
    ) {}
}
