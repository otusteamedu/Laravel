<?php

namespace App\Services\UseCases\Commands\Telegram\Todo\UserAdd;

use App\Services\Telegram\Common\Send;
use App\Services\Telegram\TelegramService;
use App\Services\Telegram\Common\Recipient;
use App\Services\Telegram\Common\SendResult;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\UseCases\Commands\Telegram\Todo\UserAdd\Command;
use App\Services\UseCases\Commands\Telegram\Exceptoins\NotHasTelegramId;

class Handler
{
    /**
     * Summary of __construct
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param TodoRepositoryInterface $todoRepository
     * @param TelegramService $service
     */
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository,
        public TodoRepositoryInterface $todoRepository,
        public TelegramService $service
    ) {}

    /**
     * Summary of handle
     * @param Command $command
     * @throws NotHasTelegramId
     * @return SendResult
     */
    public function handle(Command $command): SendResult
    {
        $user = $this->userRepository->find($command->userId, true);

        if (!$user->profile->telegram_id) {
            throw new NotHasTelegramId("В профиле пользователя {$user->name} не указан Telegram ID");
        }

        $project = $this->projectRepository->find($command->projectId);
        $todo = $this->todoRepository->find($command->todoId, $command->projectId);

        $message = view('notifications.telegram.assign-todo-role', [
            'role' => $command->role->value,
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
