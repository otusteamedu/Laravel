<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Number;
use App\Exceptions\ProductNotFoundException;
use Meilisearch\Client;

class CatalogController extends Controller
{
    public function __construct(
        private ProductsService $service,
        private CategoriesService $categoriesService
    ) {}

    public function index(Request $request): View
    {
        $page = (int) $request->get('page');
        $products = $this->service->getAllWithImage($page);
        $categories = $this->categoriesService->getAll();
        $currentCategory = null;
        return view('catalog.index', compact('products', 'categories', 'currentCategory'));
    }

    public function category(int $categoryId): View
    {
        $products = $this->service->getByCategoryId($categoryId);
        $categories = $this->categoriesService->getAll();
        $currentCategory = $this->categoriesService->getById($categoryId);
        return view('catalog.index', compact('products', 'categories', 'currentCategory'));
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
        ];

        return view('catalog.show', $data);
    }

    public function search(Request $request): View
    {
        $client = new Client(env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
        $index = $client->index('products');
        
        $filters = [];
        
        if ($request->has('min_price')) {
            $filters[] = "price >= {$request->input('min_price')}";
        }
        if ($request->has('max_price')) {
            $filters[] = "price <= {$request->input('max_price')}";
        }
        
        if ($request->has('brands')) {
            $brands = implode('", "', $request->input('brands'));
            $filters[] = "brand IN [\"{$brands}\"]";
        }

        if ($request->has('rating')) {
            $filters[] = "rating >= {$request->input('rating')}";
        }
        
        if ($request->has('attributes')) {
            foreach ($request->attributes as $slug => $values) {
                $values = implode('", "', $values);
                $filters[] = "attributes.slug = \"{$slug}\" AND attributes.value IN [\"{$values}\"]";
            }
        }
        
        $results = $index->search($request->input('q', ''), [
            'filter' => implode(' AND ', $filters),
            'sort' => ['price:asc'],
        ]);
        
        $productIds = collect($results->getHits())->pluck('id')->toArray();
        $products = Product::whereIn('id', $productIds)
            ->orderByRaw("FIELD(id, " . implode(',', $productIds) . ")")
            ->get();

        return view('catalog.search', compact('products'));
    }
}
