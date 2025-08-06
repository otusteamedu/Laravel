<?php

namespace App\Interfaces\Console\Commands;

use App\Application\Services\Area\AreaServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\Area\AreaRepositoryInterface;

class WarmupCache extends Command
{
    protected $signature = 'cache:warm-up';
    protected $description = 'Прогревает нужные ключи кэша';

    private AreaServiceInterface $areaService;

    public function __construct(AreaServiceInterface $areaService)
    {
        parent::__construct();
        $this->areaService = $areaService;
    }

    public function handle(): void
    {
        $this->info('Прогрев кэша - area.getAll');
        $this->areaService->prepairDataForIndex();
        $this->info('Кэш area.getAll обновлён');
    }
}