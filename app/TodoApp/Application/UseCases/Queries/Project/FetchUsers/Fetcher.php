<?php

namespace App\TodoApp\Application\UseCases\Queries\Project\FetchUsers;

use Illuminate\Support\Arr;
use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Application\DTOs\ProjectInvitedUserDTO;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
     * @param Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $project = $this->projectRepository->find($query->projectId);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $projectDTO = new ProjectDTO(
            projectId: $project->getId()->getValue(),
            name: $project->getName()->getValue(),
            description: $project->getDescription()->getValue(),
            created: $project->getCreated()
        );

        $users = $this->projectRepository->fetchUsers($query->projectId);

        $userDTOs = array_map(
            fn($projectUser) =>
            new ProjectInvitedUserDTO(
                id: $projectUser->id,
                userId: $projectUser->userId,
                name: $projectUser->name,
                email: $projectUser->email,
                roles: $projectUser->roles,
                invited: $projectUser->invited,
                joined: $projectUser->joined,
            ),
            Arr::from($users)
        );

        return new Result(
            projectDTO: $projectDTO,
            userDTOs: $userDTOs
        );
    }
}
