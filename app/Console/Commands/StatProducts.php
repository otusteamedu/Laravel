<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ProductsService;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function Laravel\Prompts\table;
use function Laravel\Prompts\info;

class StatProducts extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:products {category} {--details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show products statistics';

    /**
     * Execute the console command.
     */
    public function handle(
        ProductsService $service,
    )
    {
        $category = $this->argument('category');
        $details = $this->option('details');

        $products = $service->getByCategoryTitle($category);
        $count = $products->count();

        if ($details) {
            $productTitles = $products->pluck('title')->all();
            $productPrices = $products->pluck('price')->all();
            $productStocks = $products->pluck('stock')->all();
            $data = [];
            foreach ($productTitles as $key => $title) {
                $data[] = [$title, $productPrices[$key], $productStocks[$key]];
            }
            $headers = ['Title', 'Price', 'In Stock'];
        } else {
            $productTitles = $products->pluck('title')->all();
            $data = [];
            foreach ($productTitles as $title) {
                $data[] = [$title];
            }
            $headers = ['Title'];
        }
        
        info('Total: ' . $count);
        table($headers, $data);
    }
}
