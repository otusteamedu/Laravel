<?php

namespace App\Application\UseCases\Queries\TodoStatus\FetchForProject;

use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Todo\DTO\TodoStatusDTO;

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
