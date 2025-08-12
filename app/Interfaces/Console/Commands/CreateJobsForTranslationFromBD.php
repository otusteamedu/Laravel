<?php

namespace App\Interfaces\Console\Commands;

use App\Infrastructure\Repositories\Area\AreaRepository;
use Illuminate\Console\Command;
use App\Infrastructure\Jobs\ProcessTranclationModelField;
use Stronger21012\Autotranslator\Services\Translation\TranslatorInterface;

class CreateJobsForTranslationFromBD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-jobs-for-translation-from-BD';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавляет в очередь задачи на перевод пустых полей моделей';
    protected TranslatorInterface $translator;
    protected AreaRepository $areaRepository;


    /**
     * Execute the console command.
     */
    public function handle(
        TranslatorInterface $translator,
        AreaRepository $areaRepository,
    ) {
        $repositoryModels = [
            $areaRepository,
            // \App\Models\Category::class => ['name_en' => 'en', 'name_ru' => 'ru'],
            // \App\Models\Measure::class => ['name_en' => 'en', 'name_ru' => 'ru'],
        ];

        $langs = ['ru', 'en'];
        ProcessTranclationModelField::dispatch($repositoryModels, $langs);
    }
}
