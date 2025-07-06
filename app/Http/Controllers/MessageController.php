<?php

namespace App\Http\Controllers;

use App\Services\MessagesService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
}
