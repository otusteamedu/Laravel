<?php

namespace App\Http\Controllers\Admin;

use App\Dto\Product\StoreDto;
use App\Dto\Product\UpdateDto;
use App\Exceptions\ProductNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Number;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function __construct(
        private ProductsService $service,
        private CategoriesService $categoriesService
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = $this->service->getList();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = $this->categoriesService->getAll();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $dto = new StoreDto(
            $data['title'], 
            $data['description'],
            $data['category_id'],
            $data['price'],
            $data['stock']
        );
        
        $assets = $data['assets'];

        $this->service->add($dto, $assets);

        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     */
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

        return view('admin.products.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $productId): View
    {
        try {
            $product = $this->service->getById($productId);
        } catch (ProductNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $categories = $this->categoriesService->getAll();

        $data = [
            'productId' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'categoryId' => $product->getCategoryId(),
            'price' => $product->getPrice(),
            'stock' => $product->getStock(),
            'categories' => $categories,
        ];

        return view('admin.products.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $productId): RedirectResponse
    {
        $data = $request->validated();
        $dto = new UpdateDto(
            $productId, 
            $data['title'], 
            $data['description'],
            $data['category_id'],
            $data['price'],
            $data['stock']
        );

        $assets = $data['assets'];

        try {
            $this->service->update($dto, $assets);
        } catch (ProductNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $productId): RedirectResponse
    {
        try {
            $this->service->delete($productId);
        } catch (ProductNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Невозможно выполнить удаление']);
        }

        return redirect()->route('admin.products.index');
    }
}
