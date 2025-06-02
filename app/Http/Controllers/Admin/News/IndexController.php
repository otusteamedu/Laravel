<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Services\News\Handlers\IndexHandler;
use Illuminate\View\View;


class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(IndexHandler $indexNewsUseCase): View
    {
        $news = $indexNewsUseCase()->results;

        return view('admin.news.index', compact('news'));
    }
}
