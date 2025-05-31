<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Categories\Exceptions\CategoryNotFoundException;
use App\Services\Categories\Handlers\DestroyHandler;

class DestroyController extends Controller
{

    /**
     * Удалить категорию
     */
    public function __invoke(DestroyHandler $destroyHandler, string $categoryId)
    {
        try {
            $destroyHandler((int)$categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Category not found');
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Категория успешно удалена");
    }
}
