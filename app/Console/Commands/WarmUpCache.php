<?php

namespace App\Console\Commands;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmUpCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting cache warming...');


        $isSupportTags = $this->cacheSupportsTags();

        $perPage = 10;
        $totalProducts = $this->productRepository->count();

        $totalPages = ceil($totalProducts / $perPage);


        for ($page = 1; $page <= $totalPages; $page++) {

            $cacheKey = 'products_admin_page_' . $page . '_per_page_' . $perPage;

            if($isSupportTags){
                Cache::tags(['products'])->remember($cacheKey, 60, function () use ($page, $perPage) {
                    return $this->productRepository->getAllPaginated($perPage, 'order', $page);
                });
            }else{
                Cache::remember($cacheKey, 60 * 2, function () use ($perPage, $page) {
                    return $this->productRepository->getAllPaginated($perPage, 'order', $page);
                });
            }

            $this->info("Cached page {$page}/{$totalPages}");
        }

        $this->info('Products cache warmed successfully!');


    }

    /**
     * Check if the current cache driver supports tags.
     *
     * @return bool
     */
    protected function cacheSupportsTags(): bool
    {
        $driver = config('cache.default');
        return in_array($driver, ['redis', 'memcached']);
    }
}
