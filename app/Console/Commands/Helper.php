<?php

namespace App\Console\Commands;

use App\EloquentModels\Area;
use App\EloquentModels\MeasureProductRecipe;
use App\EloquentModels\Photo;
use App\EloquentModels\Product;
use App\EloquentModels\Recipe;
use App\EloquentModels\Video;
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
        
        die;
    }
}