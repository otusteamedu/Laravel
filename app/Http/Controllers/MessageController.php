<?php

namespace App\Http\Controllers;

use App\Dto\Message\StoreDto;
use App\Http\Requests\Message\StoreRequest;
use App\Services\MessagesService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    public function __construct(
        private MessagesService $service,
    ) {}

    public function index(): View
    {
        $messages = $this->service->getAll();
        return view('chat.index', compact('messages'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $userId = auth()->id() ?? 0;
        $dto = new StoreDto ($data['content'], $userId);

        $this->service->add($dto);

        return redirect()->route('chat.index');
    }
}
