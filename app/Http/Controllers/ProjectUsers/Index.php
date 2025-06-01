<?php

namespace App\Http\Controllers\ProjectUsers;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Services\UseCases\Queries\Project\FetchUsers\Query;
use App\Services\UseCases\Queries\Project\FetchUsers\Fetcher;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Index extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('projects.users.index', [
                'project' => $result->projectDTO,
                'users' => $result->userDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
