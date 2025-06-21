<?php

namespace App\Services\UseCases\Queries\Todo\Fetch;

use Vhar\EmbedVideo\Facades\EmbedVideo;
use App\Services\Repositories\Todo\TodoFetchDTO;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Fetcher
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoRepositoryInterface $todoRepository,
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

        $todoDTO = $this->todoRepository->find($query->todoId, $query->projectId);

        if ($todoDTO === null) {
            throw new ModelNotFoundException('Задача не найдена');
        }

        $todo = $todoDTO;

        if (!empty($todo->options['video'])) {
            $options = $todo->options;

            $options['embed'] = EmbedVideo::handle($options['video']);

            $todo = new TodoFetchDTO(
                todoId: $todo->todoId,
                title: $todo->title,
                author: $todo->author,
                status: $todo->status,
                description: $todo->description,
                deadline: $todo->deadline,
                created: $todo->created,
                updated: $todo->updated,
                options: $options,
            );
        }

        return new Result(
            projectDTO: $projectDTO,
            todoDTO: $todo,
        );
    }
}
