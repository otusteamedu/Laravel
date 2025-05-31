<?php

namespace App\Services\UseCases\Queries\Project\FetchForUser;

use Illuminate\Support\Arr;
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
            fn($project) =>
            new ProjectDTO(
                id: $project->id,
                name: $project->name,
                description: $project->description,
                created: $project->created,
            ),
            Arr::from($projects)
        );

        return new Result(
            ptojectDTOs: $projectDTOs
        );
    }
}
