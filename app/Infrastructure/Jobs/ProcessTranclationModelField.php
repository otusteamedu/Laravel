<?php

namespace App\Infrastructure\Jobs;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Domain\BusinessModels\Area;
use App\Domain\ValueObjects\Area\AreaLang;
use App\Domain\ValueObjects\Area\AreaName;
use App\Interfaces\Response\WebResponse;
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

    /**
     * Репозиторий для работы с моделью Area.
     * @var AreaRepositoryInterface
     */
    private AreaRepositoryInterface $areaRepository;
    /**
     * Репозитории, с которыми будет работать задача.
     * @var array<int, \App\Domain\Repositories\...RepositoryInterface>
     */
    public array $repositoryModels;
    /**
     * Список языков, которые будут учавствовать при переводе.
     * @var array<int, string>
     */
    public array $langs;
    /**
     * Сервис перевода.
     * @var TranslatorInterface
     */
    public TranslatorInterface $translator;

    /**
     * Create a new job instance.
     */
    public function __construct() 
    {
        $this->langs = ['ru', 'en'];
        $this->onQueue('translationModelFeild');
    }

    /**
     * Execute the job.
     * @param AreaRepositoryInterface $areaRepository
     * @param TranslatorInterface $translator
     * @return void
     */
    public function handle(
        AreaRepositoryInterface $areaRepository,
        TranslatorInterface $translator,
    ): void {
        $this->repositoryModels = [
            $areaRepository,
        ];
        foreach ($this->repositoryModels as $repositoryModel) {
            foreach ($this->langs as $lang) {
                $idsModels = $repositoryModel->getIdWhereNullField('name_' . $lang);
                $presenceLang = [];
                if (empty($idsModels)) {
                    $e = new NotFoundServiceException(
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
                        $name = new AreaName($searchedLangValue);
                        $lang = new AreaLang($lang);
                        $model = new Area(
                            name:$name,
                            lang:$lang,
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
