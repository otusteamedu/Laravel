<?php

namespace App\Console\Commands;

use App\Services\Cache\CacheServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheClearCommand extends Command
{
    protected $signature = 'cache:clear-app
                            {--tags=* : Сбросить кэш по конкретным тегам}
                            {--all : Сбросить весь кэш}';

    protected $description = 'Сброс кэша приложения с поддержкой тегов и кластерных блокировок';

    public function __construct(private CacheServiceInterface $cacheService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $lockKey = 'cache_clear_lock';
        $lockTtl = 300; // 5 минут

        // Блокировка для кластерной среды
        if (!Cache::lock($lockKey, $lockTtl)->get()) {
            $this->warn('Сброс кэша уже выполняется на другом сервере. Пропускаем...');
            return self::SUCCESS;
        }

        try {
            $this->info('Начинаем сброс кэша...');

            if ($this->option('all')) {
                $this->clearAllCache();
            } elseif ($tags = $this->option('tags')) {
                $this->clearCacheByTags($tags);
            } else {
                $this->clearApplicationCache();
            }

            $this->info('Сброс кэша завершен успешно!');

        } catch (\Exception $e) {
            $this->error("Ошибка при сбросе кэша: {$e->getMessage()}");
            return self::FAILURE;
        } finally {
            Cache::lock($lockKey)->release();
        }

        return self::SUCCESS;
    }

    private function clearAllCache(): void
    {
        $this->info('Сбрасываем весь кэш...');
        Cache::flush();
        $this->cacheService->flush();
        $this->line('- Весь кэш сброшен');
    }

    private function clearCacheByTags(array $tags): void
    {
        $this->info("Сбрасываем кэш по тегам: " . implode(', ', $tags));

        foreach ($tags as $tag) {
            Cache::tags([$tag])->flush();
            $this->line("- Кэш тега '{$tag}' сброшен");
        }
    }

    private function clearApplicationCache(): void
    {
        $this->info('Сбрасываем кэш основных компонентов...');

        $appTags = ['tasks', 'categories', 'users', 'priorities'];

        foreach ($appTags as $tag) {
            Cache::tags([$tag])->flush();
            $this->line("- Кэш '{$tag}' сброшен");
        }
    }
}
