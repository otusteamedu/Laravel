<?php

namespace App\Http\Controllers\Api\v2;

use App\Dto\Search\SearchDto;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Services\ProductsService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(private ProductsService $service) {}

    /**
     * Display a listing of found resources.
     */
    public function __invoke(Request $request)
    {
        $dto = new SearchDto(
            $request->input('q', ''),
            $request->input('category_id', ''),
            $request->input('min_price', ''),
            $request->input('max_price', ''),
            $request->input('brands', []),
            $request->input('rating', ''),
            $request->input('min_screen', ''),
            $request->input('max_screen', ''),
            $request->input('min_ram', ''),
            $request->input('max_ram', ''),
            $request->input('min_builtin', ''),
            $request->input('max_builtin', '')
        );
        $products = $this->service->search($dto);

        ProductResource::withoutWrapping();
        return ProductResource::collection($products);
    }
}
