<?php

namespace App\Http\Controllers\News;

use App\Models\News;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\NotificationNewsCreatedEvent;
class Create
{
    public function create(): View
    {
        return view('web.admin.create');
    }

    /**
     * - Используется валидатор напрямую в контроллере
     * - Используется DI для внедрения зависимостей
     * - Явно обрабатываются ошибки валидации
     */
    public function creates(
        Request $request,
        ValidationFactory $validationFactory,
        AuthManager $auth
    ): RedirectResponse
    {
        
        
        $validator = $validationFactory->make(request()->all(), [
            'name' => ['required', 'min:10', 'max: 255'],
            'text' => ['required', 'min:10'],
        ]);

        try {
            $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
            ;
        }

        $news = new News();
        $news->name = $request->input('name');
        $news->text = $request->input('text');
        $news->link = Str::slug($request->input('name'));
        $news->preview = Str::limit($request->input('text'), 20, '...');
        $news->user_id = Auth::id() ?? User::latest()->first()->id;
        $news->create_at = Carbon::now()->format('Y-m-d');
        $news->save();
        event(new NotificationNewsCreatedEvent($news->id));
        return redirect()
            ->route('news.index')
            ->with('success', 'Пост успешно создан')
        ;
    }
}