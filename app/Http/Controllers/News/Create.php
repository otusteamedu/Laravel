<?php

namespace App\Http\Controllers\News;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\Request;
use App\Application\UseCase\CreateNewsUseCase;
class Create
{
    private CreateNewsUseCase $useCase;
    /**
     * Summary of __construct
     * @param \App\Application\UseCase\CreateNewsUseCase $useCase
     */
    public function __construct(CreateNewsUseCase $useCase
    ) {
        $this->useCase = $useCase;
    }
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
      
        $this->useCase->execute($request);        
        return redirect()
            ->route('news.index')
            ->with('success', 'Пост успешно создан')
        ;
    }
}