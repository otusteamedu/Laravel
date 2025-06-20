<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchCategoryById\Query;
use App\Services\Queries\FetchCategoryById\Fetcher;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали категории
     */
    public function show(Fetcher $fetcher, string $categoryId): View
    {
        try {
            $query = new Query((int)$categoryId);
            $category = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Категория не найдена');
        }

        return view('admin.categories.show', compact('category'));
    }
}
