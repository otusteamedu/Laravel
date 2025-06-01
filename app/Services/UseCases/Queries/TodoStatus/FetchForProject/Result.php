<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\TodoStatusDTO;

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
