<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use App\Models\Project;
use App\Services\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository
    ) {}

    /**
     * Возвращает массив проектов пользователя
     * @param \App\Services\UseCases\Queries\Project\FetchForUser\Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $projects = $this->projectRepository->fetchForUser($query->userId);

        $projectDTOs = array_map(
            function (Project $project) {
                return new ProjectDTO(
                    id: $project->id,
                    name: $project->name,
                    description: $project->description,
                    created: $project->created_at,
                );
            },
            $projects
        );

        return new Result($projectDTOs);
    }
}
