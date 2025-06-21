<?php

namespace App\Services\UseCases\Queries\Todo\Fetch;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;


class Result
{
    /**
     * @param ProjectDTO $projectDTO
     * @param TodoFetchDTO $todoDTO
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public TodoFetchDTO $todoDTO,
    ) {}
}
