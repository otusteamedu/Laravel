<?php

namespace App\Interfaces\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Cache\Repository as CacheRepository;

class ClearCache extends Command
{
    protected $signature = 'project-cache:clear';
    protected $description = 'Сброс кэша раз в сутки';

    public function handle(
        CacheRepository $cache
    ): int {
        $cache->flush();
        $this->info('Кэш успешно сброшен.');
        return Command::SUCCESS;
    }
}