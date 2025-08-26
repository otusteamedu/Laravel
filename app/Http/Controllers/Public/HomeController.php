<?php

namespace App\Http\Controllers\Public;

use App\Application\UseCases\Category\Queries\FetchPopularCategories\Fetcher as PopularCategoriesFetcher;
use App\Application\UseCases\Category\Queries\FetchPopularCategories\Query as PopularCategoriesFetcherQuery;
use App\Application\UseCases\News\Queries\FetchLatestNews\Fetcher as LatestNewsFetcher;
use App\Application\UseCases\News\Queries\FetchLatestNews\Query as LatestNewsFetcherQuery;
use App\Http\Controllers\Controller;
use Illuminate\View\View;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(LatestNewsFetcher $latestNewsFetcher, PopularCategoriesFetcher $popularCategoriesFetcher): View
    {
        $latestNews = $latestNewsFetcher->fetch(new LatestNewsFetcherQuery())->items;
        $popularCategories = $popularCategoriesFetcher->fetch(new PopularCategoriesFetcherQuery())->items;

        return view('home', compact('latestNews', 'popularCategories'));
    }
}
