<?php

namespace App\Services\UseCases\Commands\Mail\Todo\UserAdd;

use App\Mail\Todo\AssignToTodoMail;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use App\Services\Repositories\UserRepositoryInterface;
use App\TodoApp\Domain\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\UseCases\Commands\Mail\Todo\UserAdd\Command;

class Handler
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository,
        public TodoRepositoryInterface $todoRepository,
    ) {}

    /**
     * Summary of handle
     * @param Command $command
     * @throws ModelNotFoundException
     * @return mixed
     */
    public function handle(Command $command): mixed
    {
        $user = $this->userRepository->find($command->userId, true);
        if (!$user) {
            throw new ModelNotFoundException("Пользователь {$command->userId} не найден");
        }

        $project = $this->projectRepository->find($command->projectId);
        if (!$project) {
            throw new ModelNotFoundException("Проект {$command->projectId} не найден");
        }

        $todo = $this->todoRepository->find($command->todoId, $command->projectId);
        if (!$todo) {
            throw new ModelNotFoundException("Задача {$command->todoId} в проекте {$command->projectId} не найдена");
        }

        return Mail::mailer('smtp')
            ->to($user->email)
            ->queue(new AssignToTodoMail(
                user: $user,
                project: $project,
                todo: $todo,
                role: $command->role
            ));
    }
}
