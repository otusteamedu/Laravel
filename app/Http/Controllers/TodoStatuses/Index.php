<?php

namespace App\Http\Controllers\TodoStatuses;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Services\UseCases\Queries\TodoStatus\FetchForProject\Query;
use App\Services\UseCases\Queries\TodoStatus\FetchForProject\Fetcher;
use App\Services\UseCases\Queries\TodoStatus\FetchForProject\ModelNotFoundException;

class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(Fetcher $fetcher, int $projectId): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('todos.statuses.index', [
                'project'  => $result->projectDTO,
                'statuses' => $result->todostatusDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
