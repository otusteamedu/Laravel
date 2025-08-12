<?php

namespace App\Infrastructure\Jobs;

use App\Domain\BusinessModels\Area;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Response\WebResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Stronger21012\Autotranslator\Services\Translation\TranslatorInterface;

class ProcessTranclationModelField implements ShouldQueue
{
    use Queueable;

    /**
     * Кол-во попыток (всего).
     * @var int
     */
    public $tries = 3;

    /**
     * Экспоненциальная задержка между попытками.
     * @return array
     */
    public function backoff(): array
    {
        return [60, 120, 240]; 
    }

    public array $repositoryModels;
    public array $langs;
    public TranslatorInterface $translator;

    /**
     * Create a new job instance.
     */
    public function __construct(
        array $repositoryModels,
        array $langs,
    ) {
        $this->repositoryModels = $repositoryModels;
        $this->langs = $langs;
        $this->onQueue('translationModelFeild');
    }

    /**
     * Execute the job.
     */
    public function handle(
        TranslatorInterface $translator,
    ): void {
        foreach ($this->repositoryModels as $repositoryModel) {
            foreach ($this->langs as $lang) {
                $idsModels = $repositoryModel->getIdWhereNullField('name_' . $lang);
                $presenceLang = [];
                if (empty($idsModels)) {
                    $e = new NotFoundException(
                        'При выполнении задачи translationModelFeild не найдены записи с пустым переводом по языку ' . $lang,
                        404
                    );
                    $response = new WebResponse(false, null, $e->getMessage(), [], $e->getCode());
                    Log::info(__METHOD__ . var_export($response, true));
                    continue;
                }
                foreach ($idsModels as $idModel) {
                    try {
                        $presenceLang = $repositoryModel->findPresenceLangById($idModel);
                        $searchedLangValue = $translator->translate($presenceLang['value'], $presenceLang['lang'], $lang);
                        $model = new Area(
                            name:$searchedLangValue,
                            id:$idModel,
                            created_at:$presenceLang['created_at'],
                        );
                        $repositoryModel->update($model, $lang);
                    } catch (\Throwable $e) {
                        $response = new WebResponse(false, null, $e->getMessage(), ['repositoryModel' => get_class($repositoryModel), 'idModel' => $idModel, 'lang' => $lang], $e->getCode());
                        Log::error(__METHOD__ . var_export($response, true));
                    }
                } 
            }
        }
    }
}
