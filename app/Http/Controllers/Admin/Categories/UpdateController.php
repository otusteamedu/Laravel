<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Commands\UpdateCategory\Command;
use App\Services\Commands\UpdateCategory\Handler;
use App\Services\Queries\FetchCategoryById\Query;
use App\Services\Queries\FetchCategoryById\Fetcher;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования категории
     */
    public function edit(Fetcher $fetcher, string $categoryId): View
    {
        try {
            $query = new Query((int)$categoryId);
            $category = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Обновить данные категории
     */
    public function update(UpdateCategoryRequest $request, Handler $handler, string $categoryId)
    {
        $request->validated();

        try {
            $command = new Command(
                id: (int)$categoryId,
                name: $request->get('name'),
                color: $request->get('color'),
                description: $request->get('description')
            );

            $category = $handler->handle($command);

            return redirect()->route('admin.categories.index')
                ->with('success', "Категория \"{$category->name}\" успешно обновлена");

        } catch (\App\Services\Exceptions\Categories\CategoryAlreadyExistsException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());

        } catch (\Exception) {
            throw new NotFoundHttpException('Категория не найдена');
        }
    }
}
