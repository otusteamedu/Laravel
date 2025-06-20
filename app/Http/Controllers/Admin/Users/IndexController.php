<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllUsers\Query;
use App\Services\Queries\FetchAllUsers\Fetcher;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * Показать список пользователей
     */
    public function index(Request $request, Fetcher $fetcher)
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
