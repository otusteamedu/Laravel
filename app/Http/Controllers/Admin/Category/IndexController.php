<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Services\Category\Handlers\IndexHandler;
use Illuminate\View\View;


class IndexController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(IndexHandler $indexCategoryUseCase): View
    {
        $categories = $indexCategoryUseCase()->results;

        return view('admin.categories.index', compact('categories'));
    }
}
