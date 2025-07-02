<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Queries\FetchLatestNews\Fetcher as LatestNewsFetcher;
use App\Services\UseCases\Queries\FetchLatestNews\Query as LatestNewsFetcherQuery;
use App\Services\UseCases\Queries\FetchPopularCategories\Fetcher as PopularCategoriesFetcher;
use App\Services\UseCases\Queries\FetchPopularCategories\Query as PopularCategoriesFetcherQuery;
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
        $latestNews = $latestNewsFetcher->fetch(new LatestNewsFetcherQuery())->results;
        $popularCategories = $popularCategoriesFetcher->fetch(new PopularCategoriesFetcherQuery())->results;

        return view('home', compact('latestNews', 'popularCategories'));
    }
}
