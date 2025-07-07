<?php

namespace App\TodoApp\Presentation\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Todo\DestroyRequest;
use App\Services\UseCases\Commands\Todo\Delete\Command;
use App\Services\UseCases\Commands\Todo\Delete\Handler;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;

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
                    todoId: $data['todo_id'],
                    projectId: $data['project_id'],
                )
            );
            return redirect()->back()->with('success', 'Задача удалена');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
