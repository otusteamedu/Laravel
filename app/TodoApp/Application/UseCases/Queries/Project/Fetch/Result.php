<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\Fetch;

use App\TodoApp\Application\DTOs\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO
     */
    public function __construct(
        public ProjectDTO $projectDTO,
    ) {}
}
