<?php

namespace App\Interfaces\Console\Commands;

use App\Application\Exceptions\ServiceException;
use App\Application\Services\Category\CategoryService;
use App\Infrastructure\Jobs\ProcessImportApiIdRecipeFromCategory;
use App\Interfaces\Response\WebResponse;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportCategories extends Command
{
    protected $signature = 'import_categories';
    protected $description = 'Импорт категорий рецептов из ThemealDB';

    /**
     * @param HttpClient $http
     * @param CategoryService $service
     * @return void
     */
    public function handle(
        HttpClient $http,
        CategoryService $service,
    ): int {
        // $responseApi = json_decode(file_get_contents(
        //     base_path('storage/info/categories.json')
        // ), true);
        $url = "https://www.themealdb.com/api/json/v1/1/categories.php";
        $response = $http->get($url);
        if ($response->failed()) {
            $this->error("Ошибка запроса: {$response->status()}");
            return Command::FAILURE;
        }
        $responseApi = $response->json();
        $existingCategoryFromApi = $service->existingCategoryFromApi();
        if (!empty($responseApi['categories'])) {
            foreach ($responseApi['categories'] as $category) {
                try {
                    if (in_array($category['idCategory'], $existingCategoryFromApi)) {
                        throw new ServiceException("Категория с api_id = {$category['idCategory']} уже существует", 200);
                    }
                    $service->store(
                        $category['strCategory'], 
                        $category['strCategoryDescription'], 
                        $category['idCategory'], 
                        'en'
                    );
                    ProcessImportApiIdRecipeFromCategory::dispatch($category['strCategory']);
                } catch (Throwable $th) {
                    $response = new WebResponse(
                        false,
                        null,
                        $th->getMessage(),
                        is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                        $th->getCode()
                    );
                    Log::error(__METHOD__ . var_export($response, true));
                    $this->error("Произошла ошибка при импорте, посмотрите в логи");
                }
            }
        } else {
            $this->error("Отсутствуют категории");
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
