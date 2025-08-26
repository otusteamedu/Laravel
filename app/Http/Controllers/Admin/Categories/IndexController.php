<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Application\UseCases\Category\Queries\FetchAllCategoriesPagination\Fetcher;
use App\Application\UseCases\Category\Queries\FetchAllCategoriesPagination\Query;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Показать список категорий
     */
    public function __invoke(Request $request, Fetcher $fetcher): View
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;

        $query = Query::fromPage($page, $perPage);
        $paginatedResult = $fetcher->fetch($query);

        // Преобразуем PaginatedResult в LengthAwarePaginator для шаблона
        $categories = new LengthAwarePaginator(
            items: $paginatedResult->items,
            total: $paginatedResult->total,
            perPage: $paginatedResult->getPerPage(),
            currentPage: $paginatedResult->getCurrentPage(),
            options: [
                       'path' => $request->url(),
                       'pageName' => 'page',
                   ]
        );

        $categories->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }
}
