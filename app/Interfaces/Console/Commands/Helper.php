<?php

namespace App\Interfaces\Console\Commands;

use App\Domain\BusinessModels\Area as BusinessModelsArea;
use App\Interfaces\CacheDecorator\Area\AreaRepositoryCacheDecorator;
use App\Infrastructure\EloquentModels\Area;
use App\Infrastructure\EloquentModels\MeasureProductRecipe;
use App\Infrastructure\EloquentModels\Photo;
use App\Infrastructure\EloquentModels\Product;
use App\Infrastructure\EloquentModels\Recipe;
use App\Infrastructure\EloquentModels\Video;
use App\Infrastructure\Repositories\Area\AreaRepository;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Application\Services\Area\AreaService;
use App\Interfaces\CacheDecorator\Area\CachedAreaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Helper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(
        AreaService $areaService,
        CachedAreaService $cachedAreaService,
    ) {
        Artisan::call('cache:warm-up');

        $startCache = microtime(true);
        $cachedAreaService->prepairDataForIndex();
        $endCache = microtime(true);
        $deltaCache = $endCache - $startCache;

        $start = microtime(true);
        $areaService->prepairDataForIndex();
        $end = microtime(true);
        $delta = $end - $start;

        $attitude = $deltaCache / $delta;

        echo "Время запроса в БД: $delta; \n" . 
            "Время запроса с помощью кеширования: $deltaCache; \n" . 
            "Отношение время кеша ко времени БД: $attitude; \n";
    }
}