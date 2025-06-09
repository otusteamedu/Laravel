<?php

namespace App\Services\UseCases\Queries\Project\Fetch;

use App\Services\Repositories\DTOs\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO
     */
    public function __construct(
        public ProjectDTO $projectDTO,
    ) {}
}
