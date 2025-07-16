<?php

namespace App\Application\UseCases\Commands\Todo\Show;

use App\Domain\Repositories\Project\DTO\ProjectDTO;
use App\Domain\Repositories\Todo\DTO\TodoUserDTO;
use App\Domain\Repositories\Todo\DTO\TodoFetchDTO;
use App\Domain\Repositories\Project\DTO\ProjectInvitedUserDTO;


class Result
{
    /**
     * Undocumented function
     *
     * @param ProjectDTO $projectDTO
     * @param TodoFetchDTO $todoDTO
     * @param TodoUserDTO[] $responsibles
     * @param TodoUserDTO[] $performers
     * @param TodoUserDTO[] $watchers
     * @param ProjectInvitedUserDTO[] $projectUsers
     */
    public function __construct(
        public ProjectDTO $projectDTO,
        public TodoFetchDTO $todoDTO,
        public array $responsibles,
        public array $performers,
        public array $watchers,
        public array $projectUsers,
    ) {}
}
