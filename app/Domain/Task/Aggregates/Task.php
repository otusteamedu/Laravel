<?php

namespace App\Domain\Task\Aggregates;

use App\Domain\Task\ValueObjects\CategoryId;
use App\Domain\Task\ValueObjects\PriorityId;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskDueDate;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Domain\Task\ValueObjects\UserId;
use Carbon\Carbon;
use InvalidArgumentException;

final class Task
{
    private function __construct(
        private ?TaskId $id,
        private TaskTitle $title,
        private TaskDescription $description,
        private UserId $executorId,
        private CategoryId $categoryId,
        private PriorityId $priorityId,
        private UserId $creatorId,
        private TaskStatus $status,
        private ?TaskDueDate $dueDate,
        private Carbon $createdAt,
        private Carbon $updatedAt
    ) {
    }

    public static function create(
        TaskTitle $title,
        TaskDescription $description,
        UserId $executorId,
        CategoryId $categoryId,
        PriorityId $priorityId,
        UserId $creatorId,
        ?TaskDueDate $dueDate = null
    ): self {
        $now = now();
        
        return new self(
            id: null,
            title: $title,
            description: $description,
            executorId: $executorId,
            categoryId: $categoryId,
            priorityId: $priorityId,
            creatorId: $creatorId,
            status: TaskStatus::new(),
            dueDate: $dueDate,
            createdAt: $now,
            updatedAt: $now
        );
    }

    public static function fromPersistence(
        TaskId $id,
        TaskTitle $title,
        TaskDescription $description,
        UserId $executorId,
        CategoryId $categoryId,
        PriorityId $priorityId,
        UserId $creatorId,
        TaskStatus $status,
        ?TaskDueDate $dueDate,
        Carbon $createdAt,
        Carbon $updatedAt
    ): self {
        return new self(
            id: $id,
            title: $title,
            description: $description,
            executorId: $executorId,
            categoryId: $categoryId,
            priorityId: $priorityId,
            creatorId: $creatorId,
            status: $status,
            dueDate: $dueDate,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    // Геттеры
    public function id(): ?TaskId
    {
        return $this->id;
    }

    public function title(): TaskTitle
    {
        return $this->title;
    }

    public function description(): TaskDescription
    {
        return $this->description;
    }

    public function executorId(): UserId
    {
        return $this->executorId;
    }

    public function categoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function priorityId(): PriorityId
    {
        return $this->priorityId;
    }

    public function creatorId(): UserId
    {
        return $this->creatorId;
    }

    public function status(): TaskStatus
    {
        return $this->status;
    }

    public function dueDate(): ?TaskDueDate
    {
        return $this->dueDate;
    }

    public function createdAt(): Carbon
    {
        return $this->createdAt;
    }

    public function updatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    // Бизнес-методы
    public function updateDetails(
        TaskTitle $title,
        TaskDescription $description,
        ?TaskDueDate $dueDate = null
    ): void {
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->updatedAt = now();
    }

    public function assignToExecutor(UserId $executorId): void
    {
        if ($this->status->isCompleted() || $this->status->isCancelled()) {
            throw new InvalidArgumentException('Нельзя переназначить завершенную или отмененную задачу');
        }

        $this->executorId = $executorId;
        $this->updatedAt = now();
    }

    public function changeCategory(CategoryId $categoryId): void
    {
        $this->categoryId = $categoryId;
        $this->updatedAt = now();
    }

    public function changePriority(PriorityId $priorityId): void
    {
        $this->priorityId = $priorityId;
        $this->updatedAt = now();
    }

    public function startWork(): void
    {
        if (!$this->status->isNew()) {
            throw new InvalidArgumentException('Можно взять в работу только новую задачу');
        }

        $this->status = TaskStatus::inProgress();
        $this->updatedAt = now();
    }

    public function complete(): void
    {
        if ($this->status->isCompleted() || $this->status->isCancelled()) {
            throw new InvalidArgumentException('Задача уже завершена или отменена');
        }

        $this->status = TaskStatus::completed();
        $this->updatedAt = now();
    }

    public function cancel(): void
    {
        if ($this->status->isCompleted()) {
            throw new InvalidArgumentException('Нельзя отменить выполненную задачу');
        }

        if ($this->status->isCancelled()) {
            throw new InvalidArgumentException('Задача уже отменена');
        }

        $this->status = TaskStatus::cancelled();
        $this->updatedAt = now();
    }

    public function setId(TaskId $id): void
    {
        if ($this->id !== null) {
            throw new InvalidArgumentException('ID задачи уже установлен');
        }

        $this->id = $id;
    }

    // Проверки доступа
    public function canBeViewedBy(UserId $userId): bool
    {
        return $this->creatorId->equals($userId) || $this->executorId->equals($userId);
    }

    public function canBeModifiedBy(UserId $userId): bool
    {
        return $this->creatorId->equals($userId) || $this->executorId->equals($userId);
    }

    public function isOverdue(): bool
    {
        return $this->dueDate?->isOverdue() ?? false;
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }
}