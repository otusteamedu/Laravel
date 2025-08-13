<?php

namespace App\Interfaces\Console\Commands;

use Illuminate\Console\Command;
use App\Infrastructure\Jobs\ProcessTranclationModelField;

class CreateJobsForTranslationFromBD extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'create-jobs-for-translation-from-BD';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Добавляет в очередь задачи на перевод пустых полей моделей';

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        ProcessTranclationModelField::dispatch();
    }
}
