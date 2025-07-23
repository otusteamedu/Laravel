<?php

namespace App\Console\Commands;

use App\BusinessModels\Area as BusinessModelsArea;
use App\CacheDecorator\Area\AreaRepositoryCacheDecorator;
use App\EloquentModels\Area;
use App\EloquentModels\MeasureProductRecipe;
use App\EloquentModels\Photo;
use App\EloquentModels\Product;
use App\EloquentModels\Recipe;
use App\EloquentModels\Video;
use App\Repositories\Area\AreaRepository;
use App\Services\Area\AreaRepositoryInterface;
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
        AreaRepository $areaRepository,
        AreaRepositoryCacheDecorator $areaRepositoryCacheDecorator,
    ) {
        Artisan::call('cache:warm-up');

        $startCache = microtime(true);
        $areaRepositoryCacheDecorator->getAll();
        $endCache = microtime(true);
        $deltaCache = $endCache - $startCache;

        $start = microtime(true);
        $areaRepository->getAll();
        $end = microtime(true);
        $delta = $end - $start;

        $attitude = $deltaCache / $delta;

        echo "Время запроса в БД: $delta; \n" . 
            "Время запроса с помощью кеширования: $deltaCache; \n" . 
            "Отношение время кеша ко времени БД: $attitude; \n";
    }
}