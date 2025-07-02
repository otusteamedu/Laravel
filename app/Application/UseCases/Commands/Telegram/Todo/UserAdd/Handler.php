<?php

namespace App\Application\UseCases\Commands\Telegram\Todo\UserAdd;


use App\Domain\Services\Telegram\DTO\Send;
use App\Domain\Services\Telegram\DTO\SendResult;
use App\Domain\Repositories\Todo\Contracts\TodoRepositoryInterface;
use App\Domain\Repositories\User\Contracts\UserRepositoryInterface;
use App\Application\UseCases\Commands\Telegram\Todo\UserAdd\Command;
use App\Domain\Services\Telegram\Contracts\TelegramServiceInterface;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Application\UseCases\Commands\Telegram\Exceptoins\NotHasTelegramIdException;

class Handler
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository,
        public TodoRepositoryInterface $todoRepository,
        public TelegramServiceInterface $service
    ) {}

    /**
     * Summary of handle
     * @param Command $command
     * @throws NotHasTelegramIdException
     * @return SendResult
     */
    public function handle(Command $command): SendResult
    {
        $user = $this->userRepository->find($command->userId, true);

        if (!$user->profile->telegram_id) {
            throw new NotHasTelegramIdException("В профиле пользователя {$user->name} не указан Telegram ID");
        }

        $project = $this->projectRepository->find($command->projectId);
        $todo = $this->todoRepository->find($command->todoId, $command->projectId);

        $message = view('notifications.telegram.assign-todo-role', [
            'role' => $command->role,
            'userName' => $user->name,
            'project' => $project,
            'todo' => $todo,
        ]);

        return $this->service->send(new Send(
            recipient: $user->profile->telegram_id,
            message: $message->render(),
        ));
    }
}
