<?php

namespace App\TodoApp\Presentation\Http\Controllers\Projects;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\TodoApp\Application\UseCases\Queries\Project\FetchForUser\Query;
use App\TodoApp\Application\UseCases\Queries\Project\FetchForUser\Fetcher;

class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Fetcher $fetcher, AuthManager $auth): View|RedirectResponse
    {
        $userId = $auth->user()->id;
        $result = $fetcher->fetch(new Query($userId));

        return view('todo-app::projects.index', compact('result'));
    }
}
