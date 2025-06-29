<?php

namespace App\Services\UseCases\Commands\Mail\Todo\UserAdd;

use App\Mail\Todo\AssignToTodoMail;
use Illuminate\Support\Facades\Mail;
use App\Services\Telegram\Common\SendReport;
use App\Services\Repositories\UserRepositoryInterface;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\Repositories\Todo\TodoRepositoryInterface;
use App\Services\UseCases\Commands\Mail\Todo\UserAdd\Command;

class Handler
{
    /**
     * Summary of __construct
     * @param UserRepositoryInterface $userRepository
     * @param ProjectRepositoryInterface $projectRepository
     * @param TodoRepositoryInterface $todoRepository
     */
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public ProjectRepositoryInterface $projectRepository,
        public TodoRepositoryInterface $todoRepository,
    ) {}

    /**
     * Summary of handle
     * @param Command $command
     * @return SendReport
     */
    public function handle(Command $command): mixed
    {
        $user = $this->userRepository->find($command->userId, true);
        $project = $this->projectRepository->find($command->projectId);
        $todo = $this->todoRepository->find($command->todoId, $command->projectId);

        return null;
        /*
        return Mail::mailer('smtp')
            ->to($user->email)
            ->queue(new AssignToTodoMail(
                user: $user,
                project: $project,
                todo: $todo,
                role: $command->role
            ));
*/
    }
}
