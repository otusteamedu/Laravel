<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Categories\Commands\CommandDTO;
use App\Services\Categories\Handlers\CreateHandler;
use App\Http\Requests\CreateCategoryRequest;

class CreateController extends Controller
{

    /**
     * Показать форму для создания категории
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Сохранить новую категорию
     */
    public function store(CreateCategoryRequest $request, CreateHandler $handler)
    {
        $request->validated();

        $category = $handler(new CommandDTO($request->get('name'), $request->get('color'), $request->get('description')));

        return redirect()->route('admin.categories.index')
            ->with('success', "Категория успешно создана");
    }
}
