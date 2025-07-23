<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\Area\AreaRepositoryInterface;

class WarmupCache extends Command
{
    protected $signature = 'cache:warm-up';
    protected $description = 'Прогревает нужные ключи кэша';

    private AreaRepositoryInterface $areaRepository;

    public function __construct(AreaRepositoryInterface $areaRepository)
    {
        parent::__construct();
        $this->areaRepository = $areaRepository;
    }

    public function handle(): void
    {
        $this->info('Прогрев кэша - area.getAll');
        $this->areaRepository->getAll();
        $this->info('Кэш area.getAll обновлён');
    }
}