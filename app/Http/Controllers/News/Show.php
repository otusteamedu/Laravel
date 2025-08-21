<?php

namespace App\Http\Controllers\News;

use App\Application\UseCase\ShowNewsUseCase;

class Show
{
    private ShowNewsUseCase $useCase;
    /**
     * Summary of __construct
     * @param \App\Application\UseCase\ShowNewsUseCase $useCase
     */
    public function __construct(ShowNewsUseCase $useCase
    ) {
        $this->useCase = $useCase;
    }
    /**
     * - Используется Route Model Binding
     * - Вместо глобальных функций для рендеринга используется сервис из DI-контейнера
     * - Модель прокидывается прямо в шаблон (не рекомендуется так делать)
     */
    public function show($news)
    {
        $news = $this->useCase->execute($news);
        return view('web.admin.show', compact('news'));
    }
}