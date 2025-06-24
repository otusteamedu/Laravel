<?php

namespace App\Console\Commands;

use App\Repositories\TeamRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Cache;

class CacheSpeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-speed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Измеряет производительность кэша';

    /**
     * Execute the console command.
     */
    public function handle(TeamRepository $teamRepository): void
    {
        Cache::forget('teams');

        $duration1 = Benchmark::measure(function () use($teamRepository) {
            $teamRepository->all();
        });

        $duration2 = Benchmark::measure(function () use ($teamRepository) {
            $teamRepository->all();
        });

        echo "Без кэша: {$duration1} сек\n";
        echo "С кэшем: {$duration2} сек\n";
    }
}
