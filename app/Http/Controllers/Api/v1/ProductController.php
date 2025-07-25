<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\StoreRequest;
use App\Http\Requests\Api\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
        ProductResource::withoutWrapping();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!$request->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validated();
        $product = Product::create($data);
        ProductResource::withoutWrapping();
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        ProductResource::withoutWrapping();
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {
        if (!$request->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $data = $request->validated();
        $product->update($data);
        productResource::withoutWrapping();
        return new productResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!auth()->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $product->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
