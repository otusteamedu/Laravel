<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Exceptions\Categories\CategoryNotFoundException;
use App\Services\UseCases\Queries\FetchCategoryById\Fetcher;
use App\Services\UseCases\Queries\FetchCategoryById\Query;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали категории
     */
    public function __invoke(Fetcher $fetcher, string $categoryId): View
    {
        try {
            $query = new Query((int)$categoryId);
            $category = $fetcher->fetch($query);
        } catch (CategoryNotFoundException) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.show', compact('category'));
    }
}
