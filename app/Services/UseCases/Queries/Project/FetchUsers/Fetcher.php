<?php

namespace App\Services\UseCases\Queries\Project\FetchUsers;

use Illuminate\Support\Arr;
use App\Services\Repositories\ProjectRepository;
use App\Services\Repositories\DTOs\ProjectInvitedUserDTO;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Fetcher
{
    public function __construct(
        private ProjectRepository $projectRepository,
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
            fn($user) =>
            new ProjectInvitedUserDTO(
                id: $user->id,
                user_id: $user->user_id,
                name: $user->name,
                email: $user->email,
                roles: $user->roles,
                invited: $user->invited,
                joined: $user->joined,
            ),
            Arr::from($users)
        );

        return new Result(
            projectDTO: $projectDTO,
            userDTOs: $userDTOs
        );
    }
}
