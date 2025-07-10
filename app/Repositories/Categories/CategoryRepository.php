<?php

namespace App\Repositories\Categories;

use App\Models\Category;
use App\Services\Cache\CacheServiceInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 час
    private const CACHE_PREFIX = 'categories';
    
    public function __construct(private CacheServiceInterface $cache)
    {
    }

    /**
     * @return Category[]
     */
    public function fetchAll(): array {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_all');
        
        return $this->cache->tags(['categories'])->remember($key, function () {
            return Category::all()->all();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Category[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_paginated', compact('limit', 'offset'));
        
        return $this->cache->tags(['categories'])->remember($key, function () use ($limit, $offset) {
            return Category::orderBy('id', 'desc')
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
        
        return $this->cache->tags(['categories'])->remember($key, function () {
            return Category::count();
        }, self::CACHE_TTL);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function existsByName(string $name): bool
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_exists_by_name', ['name' => $name]);
        
        return $this->cache->tags(['categories'])->remember($key, function () use ($name) {
            return Category::where('name', $name)->exists();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $id
     * @return Category|null
     */
    public function find(int $id): ?Category {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_id', ['id' => $id]);
        
        return $this->cache->tags(['categories'])->remember($key, function () use ($id) {
            return Category::find($id);
        }, self::CACHE_TTL);
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function save(Category $category): bool {
        $result = $category->save();
        
        if ($result) {
            // Очищаем кэш категорий
            $this->cache->tags(['categories'])->flush();
        }
        
        return $result;
    }

    /**
     * @param Category $category
     * @return bool|null
     */
    public function delete(Category $category): ?bool {
        $result = $category->delete();
        
        if ($result) {
            // Очищаем кэш категорий
            $this->cache->tags(['categories'])->flush();
        }
        
        return $result;
    }
}
