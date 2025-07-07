<?php

namespace App\Services\UseCases\Commands\Todo\Show;

use App\TodoApp\Application\DTOs\ProjectDTO;
use App\Services\Repositories\Todo\TodoUserDTO;
use App\Services\Repositories\Todo\TodoFetchDTO;
use App\TodoApp\Application\DTOs\ProjectInvitedUserDTO;


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
