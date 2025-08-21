<?php

declare(strict_types=1);

namespace App\Application\Contracts;

interface CacheInterface
{
    public function flushTagged(string $tag): void;

    public function hasTagged(string $tag, string $key): bool;

    public function hasWithTags(array $tags, string $key): bool;

    public function hasWithTag(string $tag, string $key): bool;

    /**
     * @template T
     * @param string[] $tags
     * @param string $key
     * @param int $seconds
     * @param \Closure(): T $callback
     * @return T
     */
    public function rememberWithTags(array $tags, string $key, int $seconds, \Closure $callback): mixed;

    /**
     * @param string   $tag
     * @param string   $key
     * @param int      $seconds
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function rememberWithTag(string $tag, string $key, int $seconds, \Closure $callback): mixed;
}

