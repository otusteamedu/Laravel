<?php

namespace App\Infrastructure\Jobs;

use App\Interfaces\Response\WebResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log;

class ProcessImportApiIdRecipeFromCategory implements ShouldQueue
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

    public string $category;
    public string $url;

    /**
     * Create a new job instance.
     */
    public function __construct(string $category)
    {
        $this->category = $category;
        $this->url = 'https://www.themealdb.com/api/json/v1/1/filter.php?c=' . $category;
        $this->onQueue('importApiIdRecipeFromCategory');
    }

    /**
     * Execute the job.
     * @param string $category
     * @param HttpClient $http
     * @return void
     */
    public function handle(
        HttpClient $http,
    ): void {
        $response = $http->get($this->url);
        if ($response->failed()) {
            $response = new WebResponse(
                false,
                null,
                "Ошибка при запросе рецептов категории {$this->category}",
                [],
                500
            );
            Log::error(__METHOD__ . var_export($response, true));
        }
        $responseApi = $response->json();
        if (!empty($responseApi['meals'])) {
            foreach ($responseApi['meals'] as $recipe) {
                ProcessImportAreaAndRecipeAndProductAndMeasure::dispatch($recipe['idMeal']);
            }
        } else {
            $response = new WebResponse(
                false,
                null,
                "Отсутствуют рецепты категории {$this->category}",
                [],
                500
            );
            Log::error(__METHOD__ . var_export($response, true));
        }
    }
}
