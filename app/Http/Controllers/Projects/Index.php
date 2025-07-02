<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Application\UseCases\Queries\Project\FetchForUser\Query;
use App\Application\UseCases\Queries\Project\FetchForUser\Fetcher;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;

class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Fetcher $fetcher, AuthManager $auth): View|RedirectResponse
    {
        $userId = $auth->user()->id;
        $result = $fetcher->fetch(new Query($userId));

        return view('projects.index', compact('result'));
    }
}
