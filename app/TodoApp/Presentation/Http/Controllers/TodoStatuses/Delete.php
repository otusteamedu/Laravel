<?php

namespace App\TodoApp\Presentation\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Presentation\Http\Requests\TodoStatus\DestroyRequest;
use App\TodoApp\Application\UseCases\Commands\TodoStatus\Delete\Command;
use App\TodoApp\Application\UseCases\Commands\TodoStatus\Delete\Handler;


class Delete extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function __invoke(DestroyRequest $request, Handler $handler)
    {
        $data = $request->validated();

        try {
            $handler->handle(
                new Command(
                    statusId: $data['status_id'],
                    projectId: $data['project_id'],
                )
            );
            return redirect()->back()->with('success', 'Статус для задачи удален');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
