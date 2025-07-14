<?php

namespace App\Http\Controllers;

use App\Services\CategoriesService;
use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Number;
use App\Exceptions\ProductNotFoundException;

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
}
