<?php

namespace App\Domain\Task\Factories;

use App\Domain\Task\Commands\CreateTask\Command as CreateTaskCommand;
use App\Domain\Task\Commands\UpdateTask\Command as UpdateTaskCommand;
use App\Domain\Task\Commands\ChangeTaskStatus\Command as ChangeTaskStatusCommand;
use App\Domain\Task\Commands\DeleteTask\Command as DeleteTaskCommand;
use App\Domain\Task\ValueObjects\CategoryId;
use App\Domain\Task\ValueObjects\PriorityId;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskDueDate;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Domain\Task\ValueObjects\UserId;

class TaskCommandFactory
{
    public static function createFromArray(array $data): CreateTaskCommand
    {
        return new CreateTaskCommand(
            title: TaskTitle::fromString($data['title']),
            description: TaskDescription::fromString($data['description']),
            executorId: UserId::fromInt($data['executor_id']),
            categoryId: CategoryId::fromInt($data['category_id']),
            priorityId: PriorityId::fromInt($data['priority_id']),
            creatorId: UserId::fromInt($data['creator_id']),
            dueDate: isset($data['due_date']) ? TaskDueDate::fromString($data['due_date']) : null
        );
    }

    public static function updateFromArray(int $id, array $data): UpdateTaskCommand
    {
        return new UpdateTaskCommand(
            id: TaskId::fromInt($id),
            title: TaskTitle::fromString($data['title']),
            description: TaskDescription::fromString($data['description']),
            executorId: UserId::fromInt($data['executor_id']),
            categoryId: CategoryId::fromInt($data['category_id']),
            priorityId: PriorityId::fromInt($data['priority_id']),
            dueDate: isset($data['due_date']) ? TaskDueDate::fromString($data['due_date']) : null
        );
    }

    public static function changeStatus(int $taskId, string $status): ChangeTaskStatusCommand
    {
        return new ChangeTaskStatusCommand(
            taskId: TaskId::fromInt($taskId),
            status: TaskStatus::fromString($status)
        );
    }

    public static function delete(int $id): DeleteTaskCommand
    {
        return new DeleteTaskCommand(
            id: TaskId::fromInt($id)
        );
    }
}