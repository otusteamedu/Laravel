<?php

namespace App\Repositories;

use App\Dto\Admin\Product\StoreDto;
use App\Dto\Admin\Product\UpdateDto;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsRepository
{
    const PRODUCTS_FOR_PAGE = 10;
    const CATALOG_FOR_PAGE = 12;

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchAll(): Collection
    {
        return Product::with('category')->get();
    }


    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
    public function fetchAllWithImage(): LengthAwarePaginator
    {
        return Product::with('first_image')->paginate(self::CATALOG_FOR_PAGE)->withQueryString();
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function fetchByCategoryId(int $categoryId): Collection
    {
        return Product::with('first_image')->where('category_id', $categoryId)->get();
    }

    /**
     * @return LengthAwarePaginator<array-key, Product>
     */
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

    /**
     * @return Product
     */
    public function find(int $productId): Product
    {
        $product = Product::with(['category', 'assets'])->find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @return Product
     */
    public function findShort(int $productId): Product
    {
        $product = Product::find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    /**
     * @return Collection<array-key, Product>
     */
    public function findByIds(array $product_ids): Collection
    {
        $products = Product::whereIn('id', $product_ids)->get();
        return $products;
    }

    /**
     * @return Product
     */
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

    /**
     * @return Product
     */
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
}