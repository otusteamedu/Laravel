<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
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
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('projects.show', [
                'project' => $result->ptojectDTO,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
