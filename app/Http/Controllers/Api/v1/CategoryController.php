<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\StoreRequest;
use App\Http\Requests\Api\Category\UpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        CategoryResource::withoutWrapping();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $abilities = $request->user()->currentAccessToken()->abilities;
        if (!in_array('products:modify', $abilities)) {
            return response()->json('Forbidden.', 403);
        }

        $data = $request->validated();
        $category = Category::create($data);
        CategoryResource::withoutWrapping();
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        CategoryResource::withoutWrapping();
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $abilities = $request->user()->currentAccessToken()->abilities;
        if (!in_array('products:modify', $abilities)) {
            return response()->json('Forbidden.', 403);
        }

        $data = $request->validated();
        $category->update($data);
        CategoryResource::withoutWrapping();
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $abilities = auth()->user()->currentAccessToken()->abilities;
        if (!in_array('products:modify', $abilities)) {
            return response()->json('Forbidden.', 403);
        }

        $category->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
