<?php

namespace App\Application\UseCases\Queries\Project\FetchUsers;

use Illuminate\Support\Arr;
use App\Domain\Repositories\Project\DTO\ProjectInvitedUserDTO;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Domain\Repositories\Exceptions\ModelNotFoundException;

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
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

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
