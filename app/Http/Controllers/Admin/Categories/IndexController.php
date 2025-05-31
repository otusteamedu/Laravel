<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Controllers\Controller;
use App\Services\Categories\Handlers\IndexHandler;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Обработка запроса
     */
    public function __invoke(Request $request, IndexHandler $handler): View
    {
        $perPage = $request->input('per_page', 10);
        $categories = $handler($perPage);
        return view('admin.categories.index', compact('categories'));
    }
}
