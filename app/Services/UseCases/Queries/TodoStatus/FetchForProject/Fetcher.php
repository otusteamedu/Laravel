<?php

namespace App\Services\UseCases\Queries\TodoStatus\FetchForProject;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

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
        $projectDTO = $this->projectRepository->find($query->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

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
