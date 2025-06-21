<?php

namespace App\Http\Controllers\Admin;

use App\Dto\Admin\Category\StoreDto;
use App\Dto\Admin\Category\UpdateDto;
use App\Exceptions\CategoryNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Services\CategoriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function __construct(
        private CategoriesService $service
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sort = $request->get('sort', 'id');
        $allowedSorts = config('custom.categoriesSorts');
        $sort = in_array($sort, $allowedSorts) ? $sort : 'id';

        $direction = $request->get('direction', 'asc');
        $allowedDirections= config('custom.categoriesDirections');
        $direction = in_array($direction, $allowedDirections) ? $direction: 'asc';

        $categories = $this->service->getAllWithSort($sort, $direction);
        return view('admin.categories.index', compact('categories', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $dto = new StoreDto($data['title'], $data['description']);

        $this->service->add($dto);

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $categoryId): View
    {
        try {
            $category = $this->service->getById($categoryId);
        } catch (CategoryNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = [
            'categoryId' => $category->getId(),
            'title' => $category->getTitle(),
            'description' => $category->getDescription(),
            'createdAt' => $category->getCreatedAt()->format('d.m.Y H:i'),
        ];

        return view('admin.categories.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $categoryId): View
    {
        try {
            $category = $this->service->getById($categoryId);
        } catch (CategoryNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $data = [
            'categoryId' => $category->getId(),
            'title' => $category->getTitle(),
            'description' => $category->getDescription(),
        ];

        return view('admin.categories.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, int $categoryId): RedirectResponse
    {
        $data = $request->validated();
        $dto = new UpdateDto($categoryId, $data['title'], $data['description']);

        try {
            $this->service->update($dto);
        } catch (CategoryNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $categoryId): RedirectResponse
    {
        try {
            $this->service->delete($categoryId);
        } catch (CategoryNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Невозможно выполнить удаление']);
        }

        return redirect()->route('admin.categories.index');
    }

    public function export() 
    {
        return Excel::download(new CategoriesExport($this->service), 'categories.xlsx');
    }
}
