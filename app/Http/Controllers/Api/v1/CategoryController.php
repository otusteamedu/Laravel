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
        if (!$request->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
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
        if (!$request->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
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
        if (!auth()->user()->tokenCan('products:modify')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Deleted.'], 200);
    }
}
