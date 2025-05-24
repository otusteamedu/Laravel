<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\MeasureProductRecipe;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Video;
use Illuminate\Console\Command;

class Helper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'help';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $product = MeasureProductRecipe::whereId(3)->first()->product->toArray();
        $recipe = Recipe::whereid(6)->first();
        $products = $recipe->measureProductRecipe->map(function ($item) {
            return $item->product->toArray();
        })->toArray();
        dump($products);
        die;
    }
}
