<?php

namespace App\Repositories\Tasks;

use App\Models\Task;
use App\Services\Cache\CacheServiceInterface;

class TaskRepository implements TaskRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 час
    private const CACHE_PREFIX = 'tasks';
    
    public function __construct(private CacheServiceInterface $cache)
    {
    }

    /**
     * @return Task[]
     */
    public function fetchAll(): array {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_all');
        
        return $this->cache->tags(['tasks'])->remember($key, function () {
            return Task::with(['executor', 'category', 'priority', 'creator'])->get()->all();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Task[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_paginated', compact('limit', 'offset'));
        
        return $this->cache->tags(['tasks'])->remember($key, function () use ($limit, $offset) {
            return Task::with(['executor', 'category', 'priority', 'creator'])
                ->orderBy('id', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->all();
        }, self::CACHE_TTL);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_count');
        
        return $this->cache->tags(['tasks'])->remember($key, function () {
            return Task::count();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public function find(int $id): ?Task {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_id', ['id' => $id]);
        
        return $this->cache->tags(['tasks'])->remember($key, function () use ($id) {
            return Task::with(['executor', 'category', 'priority', 'creator'])->find($id);
        }, self::CACHE_TTL);
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function save(Task $task): bool {
        $result = $task->save();
        
        if ($result) {
            // Очищаем кэш задач
            $this->cache->tags(['tasks'])->flush();
        }
        
        return $result;
    }

    /**
     * @param Task $task
     * @return bool|null
     */
    public function delete(Task $task): ?bool {
        $result = $task->delete();
        
        if ($result) {
            // Очищаем кэш задач
            $this->cache->tags(['tasks'])->flush();
        }
        
        return $result;
    }
}
