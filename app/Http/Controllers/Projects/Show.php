<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Services\UseCases\Queries\Project\Fetch\Query;
use App\Services\UseCases\Queries\Project\Fetch\Fetcher;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Show extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher): View
    {
        if (! Gate::allows('project.view', $projectId)) {
            abort(404);
        }

        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('projects.show', [
                'projectDTO' => $result->projectDTO,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
