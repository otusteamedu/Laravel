<?php

namespace App\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoStatus\StoreRequest;
use App\Services\UseCases\Commands\TodoStatus\Create\Command;
use App\Services\UseCases\Commands\TodoStatus\Create\Handler;
use App\Services\Repositories\Exceptions\CreateModelFailedException;

class Create extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(StoreRequest $request, Handler $handler)
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    project_id: $data['project_id'],
                    name: $data['name'],
                    sort: $data['sort'],
                    color: $data['color'],
                )
            );

            return redirect()->back()->with('success', 'Статус задачи для проекта добавлен');
        } catch (CreateModelFailedException $exception) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }
    }
}
