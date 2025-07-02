<?php

namespace App\Application\UseCases\Queries\Project\Fetch;

use App\Domain\Repositories\Project\DTO\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO
     */
    public function __construct(
        public ProjectDTO $projectDTO,
    ) {}
}
