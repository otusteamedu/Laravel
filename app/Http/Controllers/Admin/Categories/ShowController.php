<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Categories\Handlers\ShowHandler;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;
use App\Services\Categories\Exceptions\CategoryNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{

    /**
     * Показать детали категории
     */
    public function __invoke(ShowHandler $showCategoryUseCase, ViewFactory $view, string $categoryId)
    {
        try {
            $category = $showCategoryUseCase((int)$categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.show', compact('category'));
    }
}
