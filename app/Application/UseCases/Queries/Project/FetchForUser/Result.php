<?php

namespace App\Application\UseCases\Queries\Project\FetchForUser;

use App\Domain\Repositories\Project\DTO\ProjectDTO;


class Result
{
    /**
     * @param ProjectDTO[] $projectDTOs
     */
    public function __construct(
        public array $projectDTOs,
    ) {}
}
