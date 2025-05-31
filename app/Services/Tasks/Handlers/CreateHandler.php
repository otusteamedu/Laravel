<?php
namespace App\Services\Tasks\Handlers;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Services\Tasks\Commands\CommandDTO;

class CreateHandler{
    public function __construct(private TaskRepositoryInterface $taskRepository)
    {
    }

    public function __invoke(CommandDTO $commandDTO): bool
    {
        $task = $this->taskRepository->create();

        $task->title = $commandDTO->title;
        $task->description = $commandDTO->description;
        $task->executor_id = $commandDTO->executor_id;
        $task->category_id = $commandDTO->category_id;
        $task->priority_id = $commandDTO->priority_id;
        
        if ($commandDTO->due_date) {
            $task->due_date = $commandDTO->due_date;
        }

        return $this->taskRepository->save($task);
    }
} 