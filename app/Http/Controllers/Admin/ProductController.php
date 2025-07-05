<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $productRepository;
    protected CategoryRepositoryInterface $categoryRepository;


    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        Gate $gate
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Product::class);
        $products = $this->productRepository->getAllPaginated(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Product::class);
        $categories = $this->categoryRepository->getAll(); // Get all categories via repository
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        Gate::authorize('create', Product::class);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alias' => 'required|string|max:255|unique:products,alias',
            'text' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->except(['_token', 'image_file', 'images_files', 'categories']);

        if ($request->hasFile('image_file')) {
            $data['image'] = Storage::disk('public')->put('products', $request->file('image_file'));
        }

        $uploadedImages = [];
        if ($request->hasFile('images_files')) {
            foreach ($request->file('images_files') as $file) {
                $uploadedImages[] = Storage::disk('public')->put('products', $file);
            }
            $data['images'] = json_encode($uploadedImages);
        }

        $data['user_id'] = $user->id;

        $product = $this->productRepository->create($data);
        $this->productRepository->syncCategories($product, $request->input('categories', []));

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        $categories = $this->categoryRepository->getAll();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alias' => 'required|string|max:255|unique:products,alias,' . $product->id,
            'text' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->except(['_token', '_method', 'image_file', 'images_files', 'categories']);

        if ($request->hasFile('image_file')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = Storage::disk('public')->put('products', $request->file('image_file'));
        }

        $existingImages = $product->images ? $product->images : [];
        $newUploadedImages = [];
        if ($request->hasFile('images_files')) {
            foreach ($request->file('images_files') as $file) {
                $newUploadedImages[] = Storage::disk('public')->put('products', $file);
            }
            $data['images'] = json_encode(array_merge($existingImages, $newUploadedImages));
        }

        $this->productRepository->update($product, $data);
        $this->productRepository->syncCategories($product, $request->input('categories', []));

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        // Cleanup files before deleting the record
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->images) {
            foreach ($product->images as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }
        }

        $this->productRepository->delete($product);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
