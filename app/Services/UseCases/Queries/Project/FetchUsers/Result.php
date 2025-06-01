<?php

namespace App\Services\UseCases\Queries\Project\FetchUsers;

use App\Services\Repositories\DTOs\ProjectDTO;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;

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
