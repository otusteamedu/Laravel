<?php

namespace App\Console\Commands;

use App\Application\Contracts\CacheInterface;
use App\Application\UseCases\News\Queries\FetchLatestNews\Fetcher as LatestNewsFetcher;
use App\Application\UseCases\News\Queries\FetchLatestNews\Query as LatestNewsFetcherQuery;
use Illuminate\Console\Command;

class WarmCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm
                            {entity? : Entry name (Example: categories, news)}
                            {--f|force : force warm cache}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warms up the cache for the main entities or application pages';


    /**
     * @param CacheInterface           $cache
     * @param LatestNewsFetcher        $latestNewsFetcher
     */
    public function __construct(protected CacheInterface $cache, protected LatestNewsFetcher $latestNewsFetcher)
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


    /**
     * @param string $entity
     * @param bool   $force
     *
     * @return void
     */
    protected function warmEntityCache(string $entity, bool $force): void
    {
        switch ($entity) {
            case 'news':
                $this->warmLatestNewsCache($force);
                break;
            default:
                $this->error("Неизвестная сущность: {$entity}");
                break;
        }
    }

    /**
     * @param bool $force
     *
     * @return void
     */
    protected function warmAllCaches(bool $force): void
    {
        $this->warmPopularCategoriesCache($force);
        $this->warmLatestNewsCache($force);
    }

    /**
     * @param bool $force
     *
     * @return void
     */
    protected function warmLatestNewsCache(bool $force): void
    {
        $cacheKey = 'latest_news_list';

        if (!$force && $this->cache->hasTagged('news', $cacheKey)) {
            $this->info('Кэш последних новостей уже существует, пропускаем.');
            return;
        }

        $this->info('Начинаем прогрев кэша последних новостей...');

        $this->latestNewsFetcher->fetch(new LatestNewsFetcherQuery());

        $this->info('Кэш последних новостей успешно прогрет.');
    }
}
