<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\UseCases\Queries\Project\FetchForUser\Query;
use App\Services\UseCases\Queries\Project\FetchForUser\Fetcher;

class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Fetcher $fetcher): View
    {
        $userId = Auth::user()->id;

        $result = $fetcher->fetch(new Query($userId));

        return view('projects.index', compact('result'));
    }
}
