<?php

namespace App\Repositories;

use App\Dto\Product\StoreDto;
use App\Dto\Product\UpdateDto;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Models\ProductAsset;

class ProductsRepository
{
    public function fetchAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Product::with('category')->get();
    }

    public function find(int $productId): Product
    {
        $product = Product::with(['category', 'assets'])->find($productId);

        if (!$product) {
            throw new ProductNotFoundException();
        }

        return $product;
    }

    public function findByIds(array $product_ids): \Illuminate\Database\Eloquent\Collection
    {
        $products = Product::whereIn('id', $product_ids)->get();
        return $products;
    }

    public function fetchAssets(int $productId): \Illuminate\Database\Eloquent\Collection
    {
        $assets = Product::find($productId)->getAssets();
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