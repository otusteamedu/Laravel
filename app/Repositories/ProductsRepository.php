<?php

namespace App\Repositories;

use App\Dto\Admin\Product\StoreDto;
use App\Dto\Admin\Product\UpdateDto;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductAsset;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsRepository
{
    const PRODUCTS_FOR_PAGE = 10;
    const CATALOG_FOR_PAGE = 12;

    public function fetchAll(): Collection
    {
        return Product::with('category')->get();
    }

    public function fetchAllWithImage(): LengthAwarePaginator
    {
        return Product::with('first_image')->paginate(self::CATALOG_FOR_PAGE)->withQueryString();
    }

    public function fetchByCategoryId(int $categoryId): Collection
    {
        return Product::with('first_image')->where('category_id', $categoryId)->get();
    }

    public function fetchList(string $sort, string $direction): LengthAwarePaginator
    {
        $paginator = Product::with('category');

        if ($sort == 'category') {
            $paginator = $paginator->orderBy(Category::select('title')->whereColumn('categories.id', 'products.category_id'), $direction);
        } else {
            $paginator = $paginator->orderBy($sort, $direction);
        }

        $paginator = $paginator->paginate(self::PRODUCTS_FOR_PAGE)->withQueryString();
        
        return $paginator;
    }

    public function find(int $productId): Product
    {
        $product = Product::with(['category', 'assets'])->find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findShort(int $productId): Product
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findByIds(array $product_ids): Collection
    {
        $products = Product::whereIn('id', $product_ids)->get();
        return $products;
    }

    public function fetchAssets(int $productId): Collection
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        $assets = $product->getAssets();
        return $assets;
    }

    public function add(StoreDto $storeDto): Product
    {
        $product = new Product();
        $product->title = $storeDto->title;
        $product->description = $storeDto->description;
        $product->category_id = $storeDto->category_id;
        $product->price = $storeDto->price;
        $product->stock = $storeDto->stock;
        $product->save();

        return $product;
    }

    public function addAssets(Product $product, array $items): void
    {
        $product->assets()->createMany($items);
    }

    public function save(UpdateDto $updateDto): Product
    {
        $product = Product::find($updateDto->id);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        $product->title = $updateDto->title;
        $product->description = $updateDto->description;
        $product->category_id = $updateDto->category_id;
        $product->price = $updateDto->price;
        $product->stock = $updateDto->stock;
        $product->save();

        return $product;
    }

    public function delete(int $productId): void
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }
        
        $product->delete();
    }

    public function deleteAssets(int $productId): void
    {
        ProductAsset::where('product_id', $productId)->delete();
    }
}