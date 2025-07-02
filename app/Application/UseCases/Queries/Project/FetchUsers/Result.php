<?php

namespace App\Application\UseCases\Queries\Project\FetchUsers;

use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Project\DTO\ProjectInvitedUserDTO;

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
