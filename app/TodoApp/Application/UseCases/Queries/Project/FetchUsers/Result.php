<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\FetchUsers;

use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Application\DTOs\ProjectInvitedUserDTO;

class Result
{
    /**
     * @param ProjectDTO $projectDTO
     * @param ProjectInvitedUserDTO[] $userDTOs
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public array $userDTOs,
    ) {}
}
