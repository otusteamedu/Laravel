<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\TodoApp\Application\DTOs\ProjectDTO;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;


class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
    ) {}

    /**
     * Список статусов задач для проекта
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

        $todoStatusesDTOs = Cache::remember(
            "project_{$query->projectId}_todo_statuses",
            Carbon::now()->addDay(),
            function () use ($query) {
                return  $this->projectRepository->fetchTodoStatuses($query->projectId);
            }
        );

        return new Result(
            projectDTO: $projectDTO,
            todostatusDTOs: $todoStatusesDTOs
        );
    }
}
