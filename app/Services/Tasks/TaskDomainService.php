<?php

namespace App\Services\Tasks;

use App\Domain\Task\Commands\CreateTask\Handler as CreateTaskHandler;
use App\Domain\Task\Commands\UpdateTask\Handler as UpdateTaskHandler;
use App\Domain\Task\Commands\ChangeTaskStatus\Handler as ChangeTaskStatusHandler;
use App\Domain\Task\Commands\DeleteTask\Handler as DeleteTaskHandler;
use App\Domain\Task\Factories\TaskCommandFactory;
use App\Domain\Task\Repositories\TaskRepositoryInterface as DomainTaskRepositoryInterface;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\UserId;
use App\Services\DTO\Tasks\TaskDTO;
use App\Services\DTO\Tasks\PaginatedResult;

/**
 * Адаптер между старой архитектурой и новым DDD агрегатом
 */
class TaskDomainService
{
    public function __construct(
        private DomainTaskRepositoryInterface $taskRepository,
        private CreateTaskHandler $createTaskHandler,
        private UpdateTaskHandler $updateTaskHandler,
        private ChangeTaskStatusHandler $changeTaskStatusHandler,
        private DeleteTaskHandler $deleteTaskHandler,
    ) {
    }

    public function createTask(array $data): bool
    {
        $command = TaskCommandFactory::createFromArray($data);
        return $this->createTaskHandler->handle($command);
    }

    public function updateTask(int $id, array $data): bool
    {
        $command = TaskCommandFactory::updateFromArray($id, $data);
        return $this->updateTaskHandler->handle($command);
    }

    public function changeTaskStatus(int $taskId, string $status): bool
    {
        $command = TaskCommandFactory::changeStatus($taskId, $status);
        return $this->changeTaskStatusHandler->handle($command);
    }

    public function deleteTask(int $id): bool
    {
        $command = TaskCommandFactory::delete($id);
        return $this->deleteTaskHandler->handle($command);
    }

    public function getTaskById(int $id): ?TaskDTO
    {
        $task = $this->taskRepository->findById(TaskId::fromInt($id));
        
        if (!$task) {
            return null;
        }

        return $this->convertToDTO($task);
    }

    public function getAllTasks(): array
    {
        $tasks = $this->taskRepository->findAll();
        
        return array_map([$this, 'convertToDTO'], $tasks);
    }

    public function getTasksByExecutor(int $executorId): array
    {
        $tasks = $this->taskRepository->findByExecutor(UserId::fromInt($executorId));
        
        return array_map([$this, 'convertToDTO'], $tasks);
    }

    public function getTasksByCreator(int $creatorId): array
    {
        $tasks = $this->taskRepository->findByCreator(UserId::fromInt($creatorId));
        
        return array_map([$this, 'convertToDTO'], $tasks);
    }

    public function getPaginatedTasks(int $limit, int $offset): PaginatedResult
    {
        $tasks = $this->taskRepository->findPaginated($limit, $offset);
        $total = $this->taskRepository->count();
        
        $taskDTOs = array_map([$this, 'convertToDTO'], $tasks);
        
        return new PaginatedResult($taskDTOs, $total, $limit, $offset);
    }

    private function convertToDTO(\App\Domain\Task\Aggregates\Task $task): TaskDTO
    {
        // Для получения имен нужно обращаться к старым моделям
        $eloquentTask = \App\Models\Task::find($task->id()->value());
        $eloquentTask->load(['executor', 'category', 'priority', 'creator']);

        return new TaskDTO(
            id: $task->id()->value(),
            title: $task->title()->value(),
            description: $task->description()->value(),
            executorId: $task->executorId()->value(),
            executorName: $eloquentTask->executor ? $eloquentTask->executor->name : '',
            categoryId: $task->categoryId()->value(),
            categoryName: $eloquentTask->category ? $eloquentTask->category->name : '',
            categoryColor: $eloquentTask->category ? $eloquentTask->category->color : '',
            priorityId: $task->priorityId()->value(),
            priorityName: $eloquentTask->priority ? $eloquentTask->priority->name : '',
            creatorId: $task->creatorId()->value(),
            creatorName: $eloquentTask->creator ? $eloquentTask->creator->name : '',
            status: $task->status()->value(),
            dueDate: $task->dueDate()?->value(),
            createdAt: $task->createdAt(),
            updatedAt: $task->updatedAt(),
        );
    }
}