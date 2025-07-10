<?php

namespace App\Repositories\Users;

use App\Models\User;
use App\Services\Cache\CacheServiceInterface;

class UserRepository implements UserRepositoryInterface
{
    private const CACHE_TTL = 3600; // 1 час
    private const CACHE_PREFIX = 'users';
    
    public function __construct(private CacheServiceInterface $cache)
    {
    }

    /**
     * @return User[]
     */
    public function fetchAll(): array {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_all');
        
        return $this->cache->tags(['users'])->remember($key, function () {
            return User::all()->all();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return User[]
     */
    public function fetchPaginated(int $limit, int $offset): array
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_paginated', compact('limit', 'offset'));
        
        return $this->cache->tags(['users'])->remember($key, function () use ($limit, $offset) {
            return User::orderBy('id', 'desc')
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
        
        return $this->cache->tags(['users'])->remember($key, function () {
            return User::count();
        }, self::CACHE_TTL);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function existsByEmail(string $email): bool
    {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_exists_by_email', ['email' => $email]);
        
        return $this->cache->tags(['users'])->remember($key, function () use ($email) {
            return User::where('email', $email)->exists();
        }, self::CACHE_TTL);
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find(int $id): ?User {
        $key = $this->cache->generateKey(self::CACHE_PREFIX . '_by_id', ['id' => $id]);
        
        return $this->cache->tags(['users'])->remember($key, function () use ($id) {
            return User::find($id);
        }, self::CACHE_TTL);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function save(User $user): bool {
        $result = $user->save();
        
        if ($result) {
            // Очищаем кэш пользователей
            $this->cache->tags(['users'])->flush();
        }
        
        return $result;
    }

    /**
     * @param User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool {
        $result = $user->delete();
        
        if ($result) {
            // Очищаем кэш пользователей
            $this->cache->tags(['users'])->flush();
        }
        
        return $result;
    }
} 