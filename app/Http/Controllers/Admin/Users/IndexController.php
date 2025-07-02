<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Queries\FetchAllUsersPagination\Fetcher;
use App\Services\UseCases\Queries\FetchAllUsersPagination\Query;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Показать список пользователей
     */
    public function __invoke(Request $request, Fetcher $fetcher): View
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;

        $query = Query::fromPage($page, $perPage);
        $paginatedResult = $fetcher->fetch($query);

        // Преобразуем PaginatedResult в LengthAwarePaginator для шаблона
        $users = new LengthAwarePaginator(
            items: $paginatedResult->items,
            total: $paginatedResult->total,
            perPage: $paginatedResult->getPerPage(),
            currentPage: $paginatedResult->getCurrentPage(),
            options: [
                       'path' => $request->url(),
                       'pageName' => 'page',
                   ]
        );

        $users->withQueryString();

        return view('admin.users.index', compact('users'));
    }
}
