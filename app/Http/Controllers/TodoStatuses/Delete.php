<?php

namespace App\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use App\Services\UseCases\Commands\TodoStatus\Delete\Command;
use App\Services\UseCases\Commands\TodoStatus\Delete\Handler;
use App\Services\UseCases\Commands\TodoStatus\Delete\ModelNotFoundException;

class Delete extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(Handler $handler, int $projectId, int $statusId)
    {
        try {
            $handler->handle(
                new Command(
                    id: $statusId,
                    project_id: $projectId,
                )
            );

            return redirect()->back()->with('success', 'Статус для задачи удален');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
