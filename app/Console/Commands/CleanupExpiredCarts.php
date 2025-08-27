<?php

namespace App\Console\Commands;

use App\Application\Services\CartAppService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanupExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:cleanup
                            {--days=30 : Delete carts older than this number of days}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired carts from the database';

    /**
     * Execute the console command.
     */
    public function handle(CartAppService $cartAppService): int
    {
        $days = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');

        $this->info('Starting expired carts cleanup...');
        $this->line("Looking for carts expired more than {$days} days ago");

        if ($isDryRun) {
            $this->info('DRY RUN: No actual changes will be made');
        }

        try {
            // Вызываем сервис для очистки
            if (!$isDryRun) {
                $cartAppService->cleanupExpiredCarts();
                $this->info('Successfully cleaned up expired carts');
            } else {
                $this->info('Dry run completed. Would have cleaned up expired carts');
            }

            // Логируем выполнение
            Log::info('Expired carts cleanup completed', [
                'days' => $days,
                'dry_run' => $isDryRun,
                'timestamp' => now(),
            ]);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error during carts cleanup: {$e->getMessage()}");

            Log::error('Expired carts cleanup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}
