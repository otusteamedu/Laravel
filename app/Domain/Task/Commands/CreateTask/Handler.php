<?php

namespace App\Domain\Task\Commands\CreateTask;

use App\Domain\Task\Aggregates\Task;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Events\TaskCreated;
use Illuminate\Support\Facades\DB;

class Handler
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {
    }

    public function handle(Command $command): bool
    {
        return DB::transaction(function () use ($command) {
            $task = Task::create(
                title: $command->title,
                description: $command->description,
                executorId: $command->executorId,
                categoryId: $command->categoryId,
                priorityId: $command->priorityId,
                creatorId: $command->creatorId,
                dueDate: $command->dueDate
            );

            $saved = $this->taskRepository->save($task);
            
            if ($saved) {
                // Для события нужна старая Eloquent модель
                $eloquentTask = \App\Models\Task::find($task->id()->value());
                $eloquentTask->load(['creator', 'executor', 'category', 'priority']);
                
                TaskCreated::dispatch($eloquentTask);
            }
            
            return $saved;
        });
    }
}