<?php

namespace App\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoStatus\DestroyRequest;
use App\Services\UseCases\Commands\TodoStatus\Delete\Command;
use App\Services\UseCases\Commands\TodoStatus\Delete\Handler;
use App\Services\UseCases\Commands\TodoStatus\Delete\ModelNotFoundException;

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
                    id: $data['status_id'],
                    projectId: $data['project_id'],
                )
            );

            return redirect()->back()->with('success', 'Статус для задачи удален');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
