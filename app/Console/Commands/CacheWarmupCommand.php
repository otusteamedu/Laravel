<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Cache\CacheWarmupService;
use App\Services\Cache\CacheServiceInterface;

class CacheWarmupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup 
                            {entity=all : Сущность для прогрева кеша (all, tasks, categories, users)}
                            {--clear : Очистить кеш перед прогревом}
                            {--pages=3 : Количество страниц для прогрева (по умолчанию 3)}
                            {--limit=10 : Количество элементов на странице (по умолчанию 10)}
                            {--force : Принудительно прогреть даже если кеш уже существует}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Прогрев кеша для основных сущностей приложения (задачи, категории, пользователи)';

    /**
     * Доступные сущности для прогрева
     */
    private const AVAILABLE_ENTITIES = ['all', 'tasks', 'categories', 'users'];

    public function __construct(
        private CacheWarmupService $warmupService,
        private CacheServiceInterface $cacheService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $entity = $this->argument('entity');
        $clear = $this->option('clear');
        $pages = (int) $this->option('pages');
        $limit = (int) $this->option('limit');
        $force = $this->option('force');

        // Валидация аргументов
        if (!in_array($entity, self::AVAILABLE_ENTITIES)) {
            $this->error("Неверная сущность: {$entity}");
            $this->info("Доступные сущности: " . implode(', ', self::AVAILABLE_ENTITIES));
            return self::FAILURE;
        }

        // Валидация опций
        if ($pages < 1 || $pages > 10) {
            $this->error("Количество страниц должно быть от 1 до 10");
            return self::FAILURE;
        }

        if ($limit < 1 || $limit > 100) {
            $this->error("Количество элементов на странице должно быть от 1 до 100");
            return self::FAILURE;
        }

        // Заголовок команды
        $this->info("Прогрев кеша для сущности: {$entity}");
        $this->info("Параметры: страниц={$pages}, лимит={$limit}");

        // Очистка кеша если нужно
        if ($clear) {
            $this->handleClearCache($entity);
        }

        // Прогрев кеша
        $this->handleWarmup($entity, $pages, $limit, $force);

        $this->info("Прогрев кеша завершен успешно!");
        return self::SUCCESS;
    }

    /**
     * Очистка кеша
     */
    private function handleClearCache(string $entity): void
    {
        $this->info("Очистка кеша...");

        $this->withProgressBar([1], function () use ($entity) {
            if ($entity === 'all') {
                $this->cacheService->flush();
            } else {
                $this->cacheService->tags([$entity])->flush();
            }
        });

        $this->newLine();
        $this->info("Кеш очищен");
    }

    /**
     * Прогрев кеша
     */
    private function handleWarmup(string $entity, int $pages, int $limit, bool $force): void
    {
        $this->info("Начинаем прогрев кеша...");

        try {
            match ($entity) {
                'all' => $this->warmupAll($pages, $limit),
                'tasks' => $this->warmupTasks($pages, $limit),
                'categories' => $this->warmupCategories($pages, $limit),
                'users' => $this->warmupUsers($pages, $limit),
            };
        } catch (\Exception $e) {
            $this->error("Ошибка при прогреве кеша: " . $e->getMessage());
            return;
        }
    }

    /**
     * Прогрев всех кешей
     */
    private function warmupAll(int $pages, int $limit): void
    {
        $entities = ['tasks', 'categories', 'users'];
        
        $this->withProgressBar($entities, function (string $entity) use ($pages, $limit) {
            $this->line("  Прогреваем {$entity}...");
            match ($entity) {
                'tasks' => $this->warmupService->warmupTasks(),
                'categories' => $this->warmupService->warmupCategories(),
                'users' => $this->warmupService->warmupUsers(),
            };
        });

        $this->newLine();
    }

    /**
     * Прогрев кеша задач
     */
    private function warmupTasks(int $pages, int $limit): void
    {
        $this->line("  Прогреваем кеш задач...");
        $this->warmupService->warmupTasks();
        $this->info("  Кеш задач прогрет");
    }

    /**
     * Прогрев кеша категорий
     */
    private function warmupCategories(int $pages, int $limit): void
    {
        $this->line("  Прогреваем кеш категорий...");
        $this->warmupService->warmupCategories();
        $this->info("  Кеш категорий прогрет");
    }

    /**
     * Прогрев кеша пользователей
     */
    private function warmupUsers(int $pages, int $limit): void
    {
        $this->line("  Прогреваем кеш пользователей...");
        $this->warmupService->warmupUsers();
        $this->info("  Кеш пользователей прогрет");
    }
}
