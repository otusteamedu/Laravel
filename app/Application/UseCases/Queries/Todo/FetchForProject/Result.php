<?php

namespace App\Application\UseCases\Queries\Todo\FetchForProject;

use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Todo\DTO\TodoFetchDTO;

class Result
{
    /**
     * @param ProjectDTO $projectDTO
     * @param TodoFetchDTO[] $todoDTOs
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public array $todoDTOs,
    ) {}
}
