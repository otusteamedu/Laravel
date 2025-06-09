<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Services\Repositories\DTOs\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO[] $projectDTOs
     */
    public function __construct(
        public array $projectDTOs,
    ) {}
}
