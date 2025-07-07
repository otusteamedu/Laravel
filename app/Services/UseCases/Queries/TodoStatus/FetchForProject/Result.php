<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Application\DTOs\TodoStatusDTO;

class Result
{
    /**
     * @param ProjectDTO $projectDTO
     * @param TodoStatusDTO[] $todostatusDTOs
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public array $todostatusDTOs,
    ) {}
}
