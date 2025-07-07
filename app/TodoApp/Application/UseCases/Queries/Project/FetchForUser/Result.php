<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\FetchForUser;

use App\TodoApp\Application\DTOs\ProjectDTO;



class Result
{
    /**
     * @param ProjectDTO[] $projectDTOs
     */
    public function __construct(
        public array $projectDTOs,
    ) {}
}
