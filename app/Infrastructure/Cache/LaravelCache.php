<?php

declare(strict_types=1);

namespace App\Infrastructure\Cache;

use App\Application\Contracts\CacheInterface;
use Illuminate\Support\Facades\Cache;

class LaravelCache implements CacheInterface
{
    public function flushTagged(string $tag): void
    {
        Cache::tags($tag)->flush();
    }

    public function hasTagged(string $tag, string $key): bool
    {
        return Cache::tags($tag)->has($key);
    }

    public function hasWithTags(array $tags, string $key): bool
    {
        return Cache::tags($tags)->has($key);
    }

    public function hasWithTag(string $tag, string $key): bool
    {
        return Cache::tags($tag)->has($key);
    }

    public function rememberWithTag(string $tag, string $key, int $seconds, \Closure $callback): mixed
    {
        return Cache::tags($tag)->remember($key, $seconds, $callback);
    }

    public function rememberWithTags(array $tags, string $key, int $seconds, \Closure $callback): mixed
    {
        return Cache::tags($tags)->remember($key, $seconds, $callback);
    }
}
