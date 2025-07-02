<?php

namespace App\Services\UseCases\Commands\Telegram\Todo\UserAdd;

use App\Services\Telegram\Common\Send;
use App\Services\Telegram\Common\SendResult;
use App\Services\Telegram\TelegramServiceInterface;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\UseCases\Commands\Telegram\Todo\UserAdd\Command;
use App\Services\UseCases\Commands\Telegram\Exceptoins\NotHasTelegramIdException;

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
