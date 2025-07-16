<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;

class WarmUpCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warmup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Прогрев кэша';

    /**
     * Execute the console command.
     */
    public function handle(ProjectRepositoryInterface $repository)
    {
        $this->info('Начинаем прогрев кэша');

        $projects = $repository->fetchAll();

        if (empty($projects)) {
            $this->warn('База данных не содержит проектов');
        } else {
            $this->info('Прогревам кэш статусов задач для проектов');

            foreach ($projects as $project) {
                Cache::remember(
                    "project_{$project->projectId}_todo_statuses",
                    Carbon::now()->addDay(),
                    function () use ($project, $repository) {
                        return  $repository->fetchTodoStatuses($project->projectId);
                    }
                );
            }

            $this->info('Прогрели кэш статусов задач для проектов');
        }

        $this->info('Прогрели все, что смогли');

        return self::SUCCESS;
    }
}
