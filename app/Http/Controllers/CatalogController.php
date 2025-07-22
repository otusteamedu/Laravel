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

    public function index(Request $request): View
    {
        $page = (int) $request->get('page');
        $products = $this->service->getAllWithImage($page);
        $categories = $this->categoriesService->getAll();
        $currentCategory = null;

        $brands = $this->brandService->getAll();

        return view('catalog.index', compact('products', 'categories', 'currentCategory', 'brands'));
    }

    public function category(int $categoryId): View
    {
        $products = $this->service->getByCategoryId($categoryId);
        $categories = $this->categoriesService->getAll();
        $currentCategory = $this->categoriesService->getById($categoryId);
        $brands = $this->brandService->getAll();
        return view('catalog.index', compact('products', 'categories', 'currentCategory', 'brands'));
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
            'attributes' => $product->attributes
        ];

        return view('catalog.show', $data);
    }

    public function search(Request $request): View
    {
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 20);

        $client = new Client(env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
        $index = $client->index('products');
        
        $filters = [];
        
        if ($request->filled('min_price')) {
            $filters[] = "price >= {$request->input('min_price')}";
        }
        if ($request->filled('max_price')) {
            $filters[] = "price <= {$request->input('max_price')}";
        }
        
        if ($request->filled('brands')) {
            $brands = implode('", "', $request->input('brands'));
            $filters[] = "brand IN [\"{$brands}\"]";
        }

        if ($request->filled('rating')) {
            $filters[] = "rating >= {$request->input('rating')}";
        }

        if ($request->filled('min_screen')) {
            $filters[] = "attributes.slug = 'screen_size' AND attributes.value >= {$request->input('min_screen')}";
        }
        if ($request->filled('max_screen')) {
            $filters[] = "attributes.slug = 'screen_size' AND attributes.value <= {$request->input('max_screen')}";
        }

        if ($request->filled('min_ram')) {
            $filters[] = "attributes.slug = 'ram' AND attributes.value >= {$request->input('min_ram')}";
        }
        if ($request->filled('max_ram')) {
            $filters[] = "attributes.slug = 'ram' AND attributes.value <= {$request->input('max_ram')}";
        }

        if ($request->filled('min_builtin')) {
            $filters[] = "attributes.slug = 'builtin_memory' AND attributes.value >= {$request->input('min_builtin')}";
        }
        if ($request->filled('max_builtin')) {
            $filters[] = "attributes.slug = 'builtin_memory' AND attributes.value <= {$request->input('max_builtin')}";
        }

        if ($request->filled('os')) {
            $values = implode('", "', $request->input('os'));
            $filters[] = "attributes.slug = 'os' AND attributes.value IN [\"{$values}\"]";
        }
        
        $results = $index->search($request->input('q', ''), [
            'filter' => implode(' AND ', $filters),
            'sort' => ['price:asc'],
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
        ]);

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

        return view('catalog.search', compact('products'));
    }
}
