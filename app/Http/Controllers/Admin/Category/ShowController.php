<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Services\Category\Exceptions\CategoryNotFoundException;
use App\Services\Category\Handlers\ShowHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\View\View;

class ShowController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param ShowHandler $showCategoryUseCase
     * @param ViewFactory $view
     * @param int         $categoryId
     *
     * @return View
     */
    public function __invoke(ShowHandler $showCategoryUseCase, ViewFactory $view, int $categoryId): View
    {
        try {
            $category = $showCategoryUseCase($categoryId);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Category not found');
        }

        return $view->make('admin.categories.show', compact('category'));
    }
}
