<?php

namespace App\Http\Controllers;

use App\Services\CategoriesService;
use App\Services\ProductsService;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function __construct(
        private ProductsService $service,
        private CategoriesService $categoriesService
    ) {}

    public function index(): View
    {
        $products = $this->service->getAllWithImage();
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
}
