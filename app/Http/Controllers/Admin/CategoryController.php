<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    protected CategoryRepositoryInterface $categoryRepository;


    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
    )
    {
        $this->categoryRepository = $categoryRepository;

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Category::class);
        $categories = $this->categoryRepository->getAllPaginated(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Category::class);
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        Gate::authorize('create', Category::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alias' => 'required|string|max:255|unique:categories,alias',
            'text' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['user_id'] = $user->id;

        $this->categoryRepository->create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category) // Category model still resolved by route model binding
    {
        Gate::authorize('update', $category);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) // Category model still resolved by route model binding
    {
        Gate::authorize('update', $category);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'alias' => 'required|string|max:255|unique:categories,alias,' . $category->id,
            'text' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $this->categoryRepository->update($category, $validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) // Category model still resolved by route model binding
    {
        Gate::authorize('delete', $category);

        $this->categoryRepository->delete($category);

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }
}
