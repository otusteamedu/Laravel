<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Commands\Project\Delete\Command;
use App\Services\UseCases\Commands\Project\Delete\Handler;
use App\Services\UseCases\Commands\Project\Delete\ModelNotFoundException;

class Delete extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(Handler $handler, int $projectId)
    {
        try {
            $handler->handle(
                new Command(
                    id: $projectId,
                )
            );

            return redirect(route('projects.index'))->with('success', 'Проект удален');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
