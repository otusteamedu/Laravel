<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Services\Home\HomeServiceInterface;
use App\Infrastructure\Helpers\LocaleHelper;
use App\Interfaces\Http\Requests\Home\GetRecipeRequest;
use App\Interfaces\Response\WebResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    public HomeServiceInterface $homeService;

    public function __construct(HomeServiceInterface $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index(): Response
    {
        try {
            $data = $this->homeService->prepairDataForIndex();
            $response = new WebResponse(true, $data, 'Успешно');
        } catch (\Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->view('home.home', compact('response'), $response->statusCode);
        }
    }

    public function getRecipe(
        GetRecipeRequest $request
    ) {
        try {
            $products = $request->get('products');
            $portions = $request->get('portions');
            $recipes = $this->homeService->getRecipe($products, (int) $portions);
            $data = view('home.listRecipe', ['recipes' => $recipes])->render();
            $response = new WebResponse(true, $data, 'Успешно');
        } catch (\Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }

    public function getMeasureByProduct(
        string $id
    ): JsonResponse {
        try {
            $productId = (int) $id;
            $data = $this->homeService->getMeasureByProduct($productId, LocaleHelper::getLocale());
            $response = new WebResponse(true, $data, 'Успешно');
        } catch (\Throwable $th) {
            $response = new WebResponse(
                false,
                null,
                $th->getMessage(),
                is_null($th->getPrevious()) ? [] : ['error' => $th->getPrevious()->getMessage()],
                $th->getCode()
            );
            Log::error(__METHOD__ . var_export($response, true));
        } finally {
            return response()->json($response->toArray(), $response->statusCode);
        }
    }
}
