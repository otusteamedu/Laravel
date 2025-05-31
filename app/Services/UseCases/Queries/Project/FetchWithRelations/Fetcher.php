<?php

namespace App\Services\UseCases\Queries\Project\FetchWithRelations;


use App\Services\Repositories\ProjectRepositoryInterface;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
     * @param \App\Services\UseCases\Queries\Project\FetchWithRelations\Query $query
     * @return Result
     */
    public function fetch(Query $query): Result
    {
        $project = $this->projectRepository->find($query->projectId);

        if ($project === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $projectDTO = new ProjectDTO(
            id: $project->id,
            name: $project->name,
            description: $project->description,
            created: $project->created,
        );

        return new Result(
            ptojectDTO: $projectDTO,
        );
    }
}
