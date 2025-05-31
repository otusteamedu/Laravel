<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Categories\Commands\CommandDTO;
use App\Services\Categories\Exceptions\CategoryNotFoundException;
use App\Services\Categories\Handlers\EditHandler;
use App\Services\Categories\Handlers\UpdateHandler;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования категории
     */
    public function edit(EditHandler $handler, string $categoryId): View
    {
        try {
            $category = $handler((int)$categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Обновить данные категории
     */
    public function update(UpdateCategoryRequest $request, UpdateHandler $handler, string $categoryId)
    {
        $request->validated();

        /** @var  $postDTO */
        $postDTO = $handler(new CommandDTO($request->get('name'), $request->get('color'), $request->get('description'), (int)$categoryId));

        return redirect()->route('admin.categories.index')
            ->with('success', "Категория успешно обновлена");
    }
}
