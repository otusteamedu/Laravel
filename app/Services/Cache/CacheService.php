<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Cache\TaggedCache;

class CacheService implements CacheServiceInterface
{
    private ?TaggedCache $taggedCache = null;
    private int $defaultTtl = 3600; // 1 час по умолчанию
    
    public function get(string $key): mixed
    {
        return $this->getCache()->get($key);
    }
    
    public function put(string $key, mixed $value, int $seconds = null): bool
    {
        return $this->getCache()->put($key, $value, $seconds ?? $this->defaultTtl);
    }
    
    public function remember(string $key, callable $callback, int $seconds = null): mixed
    {
        return $this->getCache()->remember($key, $seconds ?? $this->defaultTtl, $callback);
    }
    
    public function forget(string $key): bool
    {
        return $this->getCache()->forget($key);
    }
    
    public function flush(): bool
    {
        return $this->getCache()->flush();
    }
    
    public function tags(array $tags): self
    {
        $this->taggedCache = Cache::tags($tags);
        return $this;
    }
    
    public function generateKey(string $prefix, array $params = []): string
    {
        $paramString = '';
        if (!empty($params)) {
            ksort($params);
            $paramString = '_' . md5(serialize($params));
        }
        
        return $prefix . $paramString;
    }
    
    private function getCache()
    {
        if ($this->taggedCache) {
            $cache = $this->taggedCache;
            $this->taggedCache = null; // Сбрасываем после использования
            return $cache;
        }
        
        return Cache::store();
    }
} 