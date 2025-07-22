<?php

namespace App\Services\Commands\CreateTask;

use App\Events\TaskCreated;
use App\Models\Task;
use App\Repositories\Tasks\TaskRepositoryInterface;
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
            $task = new Task();

            $task->title = $command->title;
            $task->description = $command->description;
            $task->executor_id = $command->executorId;
            $task->category_id = $command->categoryId;
            $task->priority_id = $command->priorityId;
            $task->creator_id = $command->creatorId;
            $task->status = $command->status;

            if ($command->dueDate) {
                $task->due_date = $command->dueDate;
            }

            $saved = $this->taskRepository->save($task);
            
            if ($saved) {
                // Загружаем связанные модели для события
                $task->load(['creator', 'executor', 'category', 'priority']);
                
                // Отправляем событие в очередь (after_commit=true гарантирует отправку после commit)
                TaskCreated::dispatch($task);
            }
            
            return $saved;
        });
    }
} 