<?php

namespace App\Infrastructure\Task\Repositories;

use App\Domain\Task\Aggregates\Task as TaskAggregate;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\ValueObjects\CategoryId;
use App\Domain\Task\ValueObjects\PriorityId;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskDueDate;
use App\Domain\Task\ValueObjects\TaskId;
use App\Domain\Task\ValueObjects\TaskStatus;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Domain\Task\ValueObjects\UserId;
use App\Models\Task as EloquentTask;
use App\Services\Cache\CacheServiceInterface;
use Carbon\Carbon;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    private const CACHE_TTL = 3600;
    private const CACHE_PREFIX = 'domain_tasks';

    public function __construct(
        private CacheServiceInterface $cache
    ) {
    }

    public function save(TaskAggregate $task): bool
    {
        $eloquentTask = $task->id() 
            ? EloquentTask::find($task->id()->value())
            : new EloquentTask();

        if (!$eloquentTask) {
            return false;
        }

        $eloquentTask->title = $task->title()->value();
        $eloquentTask->description = $task->description()->value();
        $eloquentTask->executor_id = $task->executorId()->value();
        $eloquentTask->category_id = $task->categoryId()->value();
        $eloquentTask->priority_id = $task->priorityId()->value();
        $eloquentTask->creator_id = $task->creatorId()->value();
        $eloquentTask->status = $task->status()->value();
        $eloquentTask->due_date = $task->dueDate()?->value();
        $eloquentTask->updated_at = $task->updatedAt();

        $saved = $eloquentTask->save();

        if ($saved && !$task->id()) {
            $task->setId(TaskId::fromInt($eloquentTask->id));
        }

        if ($saved) {
            $this->cache->tags(['tasks'])->flush();
        }

        return $saved;
    }

    public function findById(TaskId $id): ?TaskAggregate
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_id', ['id' => $id->value()]);

        return $this->cache->tags(['tasks'])->remember($key, function () use ($id) {
            $eloquentTask = EloquentTask::find($id->value());
            
            return $eloquentTask ? $this->mapToAggregate($eloquentTask) : null;
        }, self::CACHE_TTL);
    }

    public function findAll(): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_all');

        return $this->cache->tags(['tasks'])->remember($key, function () {
            return EloquentTask::all()
                ->map(fn($task) => $this->mapToAggregate($task))
                ->toArray();
        }, self::CACHE_TTL);
    }

    public function findByExecutor(UserId $executorId): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_executor', ['executor_id' => $executorId->value()]);

        return $this->cache->tags(['tasks'])->remember($key, function () use ($executorId) {
            return EloquentTask::where('executor_id', $executorId->value())
                ->get()
                ->map(fn($task) => $this->mapToAggregate($task))
                ->toArray();
        }, self::CACHE_TTL);
    }

    public function findByCreator(UserId $creatorId): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_creator', ['creator_id' => $creatorId->value()]);

        return $this->cache->tags(['tasks'])->remember($key, function () use ($creatorId) {
            return EloquentTask::where('creator_id', $creatorId->value())
                ->get()
                ->map(fn($task) => $this->mapToAggregate($task))
                ->toArray();
        }, self::CACHE_TTL);
    }

    public function delete(TaskId $id): bool
    {
        $deleted = EloquentTask::destroy($id->value());
        
        if ($deleted) {
            $this->cache->tags(['tasks'])->flush();
        }

        return $deleted > 0;
    }

    public function count(): int
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_count');

        return $this->cache->tags(['tasks'])->remember($key, function () {
            return EloquentTask::count();
        }, self::CACHE_TTL);
    }

    public function findPaginated(int $limit, int $offset): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_paginated', compact('limit', 'offset'));

        return $this->cache->tags(['tasks'])->remember($key, function () use ($limit, $offset) {
            return EloquentTask::orderBy('id', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->map(fn($task) => $this->mapToAggregate($task))
                ->toArray();
        }, self::CACHE_TTL);
    }

    private function mapToAggregate(EloquentTask $eloquentTask): TaskAggregate
    {
        return TaskAggregate::fromPersistence(
            id: TaskId::fromInt($eloquentTask->id),
            title: TaskTitle::fromString($eloquentTask->title),
            description: TaskDescription::fromString($eloquentTask->description),
            executorId: UserId::fromInt($eloquentTask->executor_id),
            categoryId: CategoryId::fromInt($eloquentTask->category_id),
            priorityId: PriorityId::fromInt($eloquentTask->priority_id),
            creatorId: UserId::fromInt($eloquentTask->creator_id),
            status: TaskStatus::fromString($eloquentTask->status ?? 'новая'),
            dueDate: $eloquentTask->due_date ? TaskDueDate::fromPersistence($eloquentTask->due_date) : null,
            createdAt: $eloquentTask->created_at ?? now(),
            updatedAt: $eloquentTask->updated_at ?? now()
        );
    }
}