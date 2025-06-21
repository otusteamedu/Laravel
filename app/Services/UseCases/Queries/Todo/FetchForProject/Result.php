<?php

namespace App\Services\UseCases\Queries\Todo\FetchForProject;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;

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
