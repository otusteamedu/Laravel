<?php

namespace App\Console\Commands;

use App\Services\Cache\CacheWarmupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheWarmupCommand extends Command
{
    protected $signature = 'cache:warmup
                            {--components=* : Компоненты для прогрева (tasks, categories, users)}
                            {--all : Прогреть все компоненты}';

    protected $description = 'Прогрев кэша приложения с поддержкой кластерных блокировок';

    public function __construct(private CacheWarmupService $warmupService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $lockKey = 'cache_warmup_lock';
        $lockTtl = 600; // 10 минут

        // Блокировка для кластерной среды
        $lock = Cache::lock($lockKey, $lockTtl);

        if (!$lock->get()) {
            $this->warn('Прогрев кэша уже выполняется на другом сервере. Пропускаем...');
            return self::SUCCESS;
        }

        try {
            $this->info('Начинаем прогрев кэша...');

            if ($this->option('all')) {
                $this->warmupAllComponents();
            } elseif ($components = $this->option('components')) {
                $this->warmupSpecificComponents($components);
            } else {
                $this->warmupAllComponents();
            }

            $this->info('Прогрев кэша завершен успешно!');

        } catch (\Exception $e) {
            $this->error("Ошибка при прогреве кэша: {$e->getMessage()}");
            return self::FAILURE;
        } finally {
            $lock->release();
        }

        return self::SUCCESS;
    }

    private function warmupAllComponents(): void
    {
        $this->info('Прогреваем все компоненты...');

        $this->warmupTasks();
        $this->warmupCategories();
        $this->warmupUsers();
    }

    private function warmupSpecificComponents(array $components): void
    {
        $this->info("Прогреваем компоненты: " . implode(', ', $components));

        foreach ($components as $component) {
            match ($component) {
                'tasks' => $this->warmupTasks(),
                'categories' => $this->warmupCategories(),
                'users' => $this->warmupUsers(),
                default => $this->warn("Неизвестный компонент: {$component}")
            };
        }
    }

    private function warmupTasks(): void
    {
        $this->line('Прогреваем кэш задач...');

        try {
            $this->warmupService->warmupTasks();
            $this->info('Кэш задач прогрет');
        } catch (\Exception $e) {
            $this->error("Ошибка прогрева задач: {$e->getMessage()}");
        }
    }

    private function warmupCategories(): void
    {
        $this->line('Прогреваем кэш категорий...');

        try {
            $this->warmupService->warmupCategories();
            $this->info('Кэш категорий прогрет');
        } catch (\Exception $e) {
            $this->error("Ошибка прогрева категорий: {$e->getMessage()}");
        }
    }

    private function warmupUsers(): void
    {
        $this->line('Прогреваем кэш пользователей...');

        try {
            $this->warmupService->warmupUsers();
            $this->info('Кэш пользователей прогрет');
        } catch (\Exception $e) {
            $this->error("Ошибка прогрева пользователей: {$e->getMessage()}");
        }
    }
}
