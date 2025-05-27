<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Services\Category\Handlers\CreateHandler;
use App\Services\Category\Commands\CommandDTO;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreateController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request, CreateHandler $createCategoryUseCase): RedirectResponse
    {
        $request->validated();

        $createCategoryUseCase(new CommandDTO($request->get('name'), $request->get('sort')));

        return redirect()->route('admin.categories.index');
    }
}
