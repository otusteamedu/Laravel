<?php

namespace App\Http\Controllers;

use App\Services\News\Handlers\GetLatestHandler as GetLatestNewsHandler;
use App\Services\Category\Handlers\GetPopularHandler as GetPopularCategoryHandler;
use Illuminate\View\View;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(GetLatestNewsHandler $getLatestNewsUseCase, GetPopularCategoryHandler $popularCategoryHandler): View
    {
        $latestNews = $getLatestNewsUseCase()->results;
        $popularCategories = $popularCategoryHandler()->results;

        return view('home', compact('latestNews', 'popularCategories'));
    }
}
