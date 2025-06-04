<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllCategories\Query;
use App\Services\Queries\FetchAllCategories\Fetcher;

class IndexController extends Controller
{
    /**
     * Показать список категорий
     */
    public function index(Fetcher $fetcher)
    {
        $query = new Query(perPage: 10);
        $categories = $fetcher->fetch($query);

        return view('admin.categories.index', compact('categories'));
    }
}
