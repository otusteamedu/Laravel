<?php

namespace App\Console\Commands;

use App\Services\Category\Handlers\GetPopularHandler;
use App\Services\News\Handlers\GetLatestHandler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-news
                            {entity? : Entry name (Example: categories, news)}
                            {--f|force : force warm cache}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warms up the cache for the main entities or application pages';


    public function __construct(protected GetLatestHandler $latestNewsUseCase, protected GetPopularHandler $popularCategoriesHandler)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $entity = $this->argument('entity');
        $force = $this->option('force');

        if ($entity) {
            $this->info("Прогрев кэша для сущности: {$entity}");
            $this->warmEntityCache($entity, $force);
        } else {
            $this->info('Прогрев кэша для всех основных сущностей...');
            $this->warmAllCaches($force);
        }

        $this->info('Прогрев кэша завершён.');
    }


    protected function warmEntityCache(string $entity, bool $force): void
    {
        switch ($entity) {
            case 'categories':
                $this->warmPopularCategoriesCache($force);
                break;
            case 'news':
                $this->warmLatestNewsCache($force);
                break;
            default:
                $this->error("Неизвестная сущность: {$entity}");
                break;
        }
    }

    protected function warmAllCaches(bool $force): void
    {
        $this->warmPopularCategoriesCache($force);
        $this->warmLatestNewsCache($force);
    }

    protected function warmPopularCategoriesCache(bool $force): void
    {
        $cacheKey = 'popular_categories_list';

        if (!$force && Cache::tags(['categories', 'news_count'])->has($cacheKey)) {
            $this->info('Кэш популярных категорий уже существует, пропускаем.');
            return;
        }

        $this->info('Начинаем прогрев кэша популярных категорий...');
        $this->popularCategoriesHandler->__invoke();
        $this->info('Кэш популярных категорий успешно прогрет.');
    }

    protected function warmLatestNewsCache(bool $force): void
    {
        $cacheKey = 'latest_news_list';

        if (!$force && Cache::tags(['news'])->has($cacheKey)) {
            $this->info('Кэш последних новостей уже существует, пропускаем.');
            return;
        }

        $this->info('Начинаем прогрев кэша последних новостей...');
        $this->latestNewsUseCase->__invoke();
        $this->info('Кэш последних новостей успешно прогрет.');
    }
}
