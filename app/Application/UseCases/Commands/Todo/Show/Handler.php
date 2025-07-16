<?php

namespace App\Application\UseCases\Commands\Todo\Show;

use Vhar\EmbedVideo\Facades\EmbedVideo;
use App\Domain\Repositories\Todo\DTO\TodoFetchDTO;
use App\Domain\Repositories\Todo\ValueObject\TodoRoleEnum;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Domain\Repositories\Todo\Contracts\TodoRepositoryInterface;
use App\Domain\Repositories\Exceptions\ModelNotFoundException;

class Handler
{
    public function __construct(
        private ProjectRepositoryInterface $projectRepository,
        private TodoRepositoryInterface $todoRepository,
    ) {}

    /**
     * Возвращает данные для страницы проекта
     * @param Command $command
     * @return Result
     */
    public function handle(Command $command): Result
    {
        $projectDTO = $this->projectRepository->find($command->projectId);

        if ($projectDTO === null) {
            throw new ModelNotFoundException('Проект не найден');
        }

        $todoDTO = $this->todoRepository->find($command->todoId, $command->projectId);

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

        $projectUsers = $this->projectRepository->fetchUsers($command->projectId);
        $responsibles = $this->todoRepository->fetchUsersByRole($command->todoId, TodoRoleEnum::RESPONSIBLE);
        $performers = $this->todoRepository->fetchUsersByRole($command->todoId, TodoRoleEnum::PERFORMER);
        $watchers = $this->todoRepository->fetchUsersByRole($command->todoId, TodoRoleEnum::WATCHER);

        return new Result(
            projectDTO: $projectDTO,
            todoDTO: $todo,
            responsibles: $responsibles,
            performers: $performers,
            watchers: $watchers,
            projectUsers: $projectUsers,
        );
    }
}
