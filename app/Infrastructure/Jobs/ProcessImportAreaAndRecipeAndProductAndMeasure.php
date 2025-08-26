<?php

namespace App\Infrastructure\Jobs;

use App\Application\Exceptions\ServiceException;
use App\Application\Services\Area\AreaService;
use App\Application\Services\Measure\MeasureService;
use App\Application\Services\MeasureProductRecipe\MeasureProductRecipeService;
use App\Application\Services\Product\ProductService;
use App\Application\Services\Recipe\RecipeService;
use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessImportAreaAndRecipeAndProductAndMeasure implements ShouldQueue
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
        $this->onQueue('importAreaAndRecipeAndProductAndMeasure');
    }

    /**
     * Execute the job.
     * @param string $category
     * @param HttpClient $http
     * @param AreaService $areaService
     * @param RecipeService $recipeService
     * @param ProductService $productService
     * @return void
     */
    public function handle(
        HttpClient $http,
        AreaService $areaService,
        RecipeService $recipeService,
        ProductService $productService,
        MeasureService $measureService,
        MeasureProductRecipeService $measureProductRecipeService,
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
                        $response = new WebResponse(
                            false,
                            null,
                            "Рецепт с api_id = {$recipe['idMeal']} уже существует",
                            [],
                            200
                        );
                        Log::info(__METHOD__ . var_export($response, true));
                        continue;
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
                    for ($i = 1; $i < 21; $i++) {
                        if (!empty($recipe["strIngredient{$i}"])) {
                            $productService->store(
                                $recipe["strIngredient{$i}"],
                                'en'
                            );
                        }
                        if (!empty($recipe["strMeasure{$i}"])) {
                            $measureAndValue = $this->parseMeasure($recipe["strMeasure{$i}"]);
                            $measureService->store(
                                $measureAndValue['measure'],
                                'en'
                            );
                        };
                        if (!empty($recipe["strIngredient{$i}"]) && !empty($recipe["strMeasure{$i}"])) {
                            $measureProductRecipeService->store(
                                $recipe['idMeal'],
                                $recipe["strIngredient{$i}"],
                                $measureAndValue['measure'],
                                $measureAndValue['value'],
                                'en'
                            );
                        }
                    }
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
            // sleep(60);
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

    private function parseMeasure(string $measure): array
    {
        $measure = trim($measure);
        if (preg_match('/^(\d+(?:[\/\.\-]\d+)?)\s*(.*)$/u', $measure, $matches) && !empty($matches[2])) {
            return [
                'value' => $matches[1], 
                'measure' => trim($matches[2]),
            ];
        }
        return [
            'value' => '',
            'measure' => $measure,
        ];
    }
}
