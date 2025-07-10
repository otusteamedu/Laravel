<?php

namespace App\Services\Cache;

interface CacheServiceInterface
{
    public function get(string $key): mixed;
    
    public function put(string $key, mixed $value, int $seconds = null): bool;
    
    public function remember(string $key, callable $callback, int $seconds = null): mixed;
    
    public function forget(string $key): bool;
    
    public function flush(): bool;
    
    public function tags(array $tags): self;
    
    public function generateKey(string $prefix, array $params = []): string;
} 