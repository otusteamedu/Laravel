<?php

namespace App\Http\Controllers;

use App\Services\News\Handlers\GetLatestHandler;
use Illuminate\View\View;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function __invoke(GetLatestHandler $getLatestNewsUseCase): View
    {
        $latestNews = $getLatestNewsUseCase()->results;

        return view('home', compact('latestNews'));
    }
}
