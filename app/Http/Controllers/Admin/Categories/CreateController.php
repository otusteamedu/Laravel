<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Commands\CreateCategory\Command;
use App\Services\Commands\CreateCategory\Handler;
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
    public function store(CreateCategoryRequest $request, Handler $handler)
    {
        $request->validated();

        $command = new Command(
            name: $request->get('name'),
            color: $request->get('color'),
            description: $request->get('description')
        );

        $handler->handle($command);

        return redirect()->route('admin.categories.index')
            ->with('success', "Категория успешно создана");
    }
}
