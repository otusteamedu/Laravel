<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use App\Services\BrandsService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Number;
use App\Exceptions\ProductNotFoundException;
use Meilisearch\Client;

class CatalogController extends Controller
{
    public function __construct(
        private ProductsService $service,
        private CategoriesService $categoriesService,
        private BrandsService $brandService
    ) {}

    public function index(Request $request, int $categoryId = null): View
    {
        $categories = $this->categoriesService->getAll();
        $brands = $this->brandService->getAll();
        $currentCategory = !empty($categoryId) ? $this->categoriesService->getById($categoryId) : null;

        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 12);

        $client = new Client(env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
        $index = $client->index('products');

        $req = [
            'q' => $request->input('q', ''),
            'min_price' => $request->input('min_price', ''),
            'max_price' => $request->input('max_price', ''),
            'brands' => $request->input('brands', []),
            'rating' => $request->input('rating', ''),
            'min_screen' => $request->input('min_screen', ''),
            'max_screen' => $request->input('max_screen', ''),
            'min_ram' => $request->input('min_ram', ''),
            'max_ram' => $request->input('max_ram', ''),
            'min_builtin' => $request->input('min_builtin', ''),
            'max_builtin' => $request->input('max_builtin', ''),
        ];

        $filters = [];

        if ($categoryId) {
            $filters[] = "category_id = $categoryId";
        }
        
        if ($req['min_price']) {
            $filters[] = "price >= {$req['min_price']}";
        }
        if ($req['max_price']) {
            $filters[] = "price <= {$req['max_price']}";
        }
        
        if ($req['brands']) {
            $values = implode(',', $req['brands']);
            $filters[] = "brand_id IN [{$values}]";
        }

        if ($req['rating']) {
            $filters[] = "rating >= {$req['rating']}";
        }

        if ($req['min_screen']) {
            $filters[] = "screen_size >= {$req['min_screen']}";
        }
        if ($req['max_screen']) {
            $filters[] = "screen_size <= {$req['max_screen']}";
        }

        if ($req['min_ram']) {
            $filters[] = "ram >= {$req['min_ram']}";
        }
        if ($req['max_ram']) {
            $filters[] = "ram <= {$req['max_ram']}";
        }

        if ($req['min_builtin']) {
            $filters[] = "builtin_memory >= {$req['min_builtin']}";
        }
        if ($req['max_builtin']) {
            $filters[] = "builtin_memory <= {$req['max_builtin']}";
        }

        $params = [
            'filter' => implode(' AND ', $filters),
            'sort' => ['price:asc'],
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
        ]; 
        $results = $index->search($req['q'], $params);

        $productIds = collect($results->getHits())->pluck('id')->toArray();
        $total = $results->getEstimatedTotalHits();

        $rawProducts = $this->service->getByIdsWithImage($productIds);

        $products = new LengthAwarePaginator(
            $rawProducts,
            $total,
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('catalog.index', compact('products', 'categories', 'currentCategory', 'brands', 'categoryId', 'req'));
    }

    public function show(int $productId): View
    {
        try {
            $product = $this->service->getById($productId);
        } catch (ProductNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = [
            'productId' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'price' => Number::format($product->getPrice(), locale: 'ru'),
            'stock' => $product->getStock(),
            'categoryTitle' => $product->getCategory()->getTitle(),
            'assets' => $product->getAssets(),
            'createdAt' => $product->getCreatedAt()->format('d.m.Y H:i'),
            'updatedAt' => $product->getUpdatedAt()->format('d.m.Y H:i'),
            'rating' => $product->getRating(),
            'brand' => $product->getBrand()->getTitle(),
            'screenSize' => $product->getScreenSize(),
            'ram' => $product->getRam(),
            'builtinMemory' => $product->getBuiltinMemory()
        ];

        return view('catalog.show', $data);
    }
}
