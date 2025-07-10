<?php

namespace App\Services\Cache;

use App\Repositories\Tasks\TaskRepositoryInterface;
use App\Repositories\Categories\CategoryRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CacheWarmupService
{
    private const WARMUP_PAGES = [1, 2, 3]; // Первые 3 страницы
    private const WARMUP_LIMIT = 10; // Количество элементов на странице

    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Прогрев всех кэшей
     */
    public function warmupAll(): void
    {
        Log::info('Начинаем прогрев кэшей');

        $this->warmupTasks();
        $this->warmupCategories();
        $this->warmupUsers();

        Log::info('Прогрев кэшей завершен');
    }

    /**
     * Прогрев кэша задач
     */
    public function warmupTasks(): void
    {
        Log::info('Прогрев кэша задач');

        // Прогреваем общий счетчик
        $this->taskRepository->count();

        // Прогреваем получение всех задач
        $this->taskRepository->fetchAll();

        // Прогреваем пагинацию
        foreach (self::WARMUP_PAGES as $page) {
            $offset = ($page - 1) * self::WARMUP_LIMIT;
            $this->taskRepository->fetchPaginated(self::WARMUP_LIMIT, $offset);
        }

        Log::info('Прогрев кэша задач завершен');
    }

    /**
     * Прогрев кэша категорий
     */
    public function warmupCategories(): void
    {
        Log::info('Прогрев кэша категорий');

        // Прогреваем общий счетчик
        $this->categoryRepository->count();

        // Прогреваем получение всех категорий
        $this->categoryRepository->fetchAll();

        // Прогреваем пагинацию
        foreach (self::WARMUP_PAGES as $page) {
            $offset = ($page - 1) * self::WARMUP_LIMIT;
            $this->categoryRepository->fetchPaginated(self::WARMUP_LIMIT, $offset);
        }

        Log::info('Прогрев кэша категорий завершен');
    }

    /**
     * Прогрев кэша пользователей
     */
    public function warmupUsers(): void
    {
        Log::info('Прогрев кэша пользователей');

        // Прогреваем общий счетчик
        $this->userRepository->count();

        // Прогреваем получение всех пользователей
        $this->userRepository->fetchAll();

        // Прогреваем пагинацию
        foreach (self::WARMUP_PAGES as $page) {
            $offset = ($page - 1) * self::WARMUP_LIMIT;
            $this->userRepository->fetchPaginated(self::WARMUP_LIMIT, $offset);
        }

        Log::info('Прогрев кэша пользователей завершен');
    }
}
