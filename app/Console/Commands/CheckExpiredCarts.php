<?php

namespace App\Console\Commands;

use App\Domain\Cart\Repositories\CartRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carts:check
                            {--days=30 : Check carts older than this number of days}
                            {--show : Show details of expired carts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired carts without deleting them';

    /**
     * Execute the console command.
     */
    public function handle(CartRepositoryInterface $cartRepository): int
    {
        $days = (int) $this->option('days');
        $showDetails = $this->option('show');

        $expirationDate = now()->subDays($days);

        $this->info("Checking for carts expired before {$expirationDate->format('Y-m-d H:i:s')}");

        // В реальной реализации нужно добавить метод в репозиторий для подсчета
        // $expiredCount = $cartRepository->countExpiredBefore($expirationDate);

        $this->info("Found approximately X expired carts (implementation needed)");

        if ($showDetails) {
            $this->line('Detailed list would be shown here');
            // В реальной реализации: $expiredCarts = $cartRepository->findExpiredBefore($expirationDate);
        }

        Log::info('Expired carts check completed', [
            'days' => $days,
            'expiration_date' => $expirationDate,
        ]);

        return Command::SUCCESS;
    }
}
