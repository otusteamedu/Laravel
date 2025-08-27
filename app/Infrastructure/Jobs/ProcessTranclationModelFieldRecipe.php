<?php

namespace App\Infrastructure\Jobs;

use App\Application\Exceptions\NotFoundServiceException;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Application\Services\Measure\MeasureRepositoryInterface;
use App\Application\Services\Product\ProductRepositoryInterface;
use App\Application\Services\Recipe\RecipeRepositoryInterface;
use App\Domain\BusinessModels\Area;
use App\Domain\BusinessModels\Recipe;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Area\AreaName;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;
use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Stronger21012\Autotranslator\Services\Translation\TranslatorInterface;

class ProcessTranclationModelFieldRecipe implements ShouldQueue
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
     * Репозиторий для работы с моделью Recipe.
     * @var RecipeRepositoryInterface
     */
    private RecipeRepositoryInterface $recipeRepository;
    
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
     * @param RecipeRepositoryInterface $recipeRepository
     * @param TranslatorInterface $translator
     * @return void
     */
    public function handle(
        RecipeRepositoryInterface $recipeRepository,
        TranslatorInterface $translator,
    ): void {
        $this->repositoryModels = [
            $recipeRepository,
        ];
        foreach ($this->repositoryModels as $repositoryModel) {
            foreach ($this->langs as $lang) {
                $idsModels = $repositoryModel->getIdWhereNullField('name_' . $lang);
                $presenceLang = [];
                if (empty($idsModels)) {
                    $e = new NotFoundServiceException(
                        'При выполнении задачи translationModelFeildRecipe не найдены записи с пустым переводом по языку ' . $lang,
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
                        $name = new RecipeName($searchedLangValue);
                        $langModel = new Lang($lang);
                        $oldModel = $recipeRepository->findById($idModel);
                        $model = new Recipe(
                            name:$name,
                            instruction:$oldModel->getInstruction(),
                            lang:$langModel,
                            apiId:$oldModel->getApiId(),
                            alternate:$oldModel->getAlternate(),
                            category:$oldModel->getCategory(),
                            area:$oldModel->getArea(),
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
