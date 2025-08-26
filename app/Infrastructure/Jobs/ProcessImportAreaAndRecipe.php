<?php

namespace App\Infrastructure\Jobs;

use App\Application\Exceptions\ServiceException;
use App\Application\Services\Area\AreaService;
use App\Application\Services\Recipe\RecipeService;
use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessImportAreaAndRecipe implements ShouldQueue
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

    public string $apiId;
    public string $url;

    /**
     * Create a new job instance.
     */
    public function __construct(string $apiIdRecipe)
    {
        $this->apiId = $apiIdRecipe;
        $this->url = 'https://www.themealdb.com/api/json/v1/1/lookup.php?i=' . $apiIdRecipe;
        $this->onQueue('ProcessImportRecipe');
    }

    /**
     * Execute the job.
     * @param string $category
     * @param HttpClient $http
     * @param AreaService $areaService
     * @param RecipeService $recipeService
     * @return void
     */
    public function handle(
        HttpClient $http,
        AreaService $areaService,
        RecipeService $recipeService,
    ): void {
        $response = $http->get($this->url);
        if ($response->failed()) {
            $response = new WebResponse(
                false,
                null,
                "Ошибка при запросе рецепта с api_id = {$this->apiId}",
                [],
                500
            );
            Log::error(__METHOD__ . var_export($response, true));
        }
        $existingAreaFromApi = $areaService->existingAreaFromNameEn();
        $existingRecipeFromApi = $recipeService->existingRecipeFromApi();
        $responseApi = $response->json();
        if (!empty($responseApi['meals'])) {
            foreach ($responseApi['meals'] as $recipe) {
                try {
                    if (in_array($recipe['idMeal'], $existingRecipeFromApi)) {
                        throw new ServiceException("Рецепт с api_id = {$recipe['idCategory']} уже существует", 200);
                    }
                    if (!in_array($recipe['strArea'], $existingAreaFromApi)) {
                        $areaService->store($recipe['strArea'], 'en');
                    }
                    $recipeService->store(
                        $recipe['strMeal'],
                        $recipe['strInstructions'],
                        'en',
                        $recipe['idMeal'], 
                        $recipe['strMealAlternate'],
                        $recipe['strCategory'], 
                        $recipe['strArea'],
                    );
                } catch (Throwable $th) {
                    $response = new WebResponse(
                        false,
                        null,
                        $th->getMessage(),
                        is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                        $th->getCode()
                    );
                    Log::error(__METHOD__ . var_export($response, true));
                }
            }
            sleep(60);
        } else {
            $response = new WebResponse(
                false,
                null,
                "Отсутствует рецепт с api_id = {$this->apiId}",
                [],
                500
            );
            Log::error(__METHOD__ . var_export($response, true));
        }
    }
}
