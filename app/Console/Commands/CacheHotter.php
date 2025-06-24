<?php

namespace App\Console\Commands;

use App\Repositories\TeamRepository;
use Illuminate\Console\Command;

class CacheHotter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-hotter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Прогревает хэш';

    /**
     * Execute the console command.
     */
    public function handle(TeamRepository $teamRepository): void
    {
        $teamRepository->all();
    }
}
