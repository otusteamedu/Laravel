<?php

namespace App\Http\Controllers\News;

use App\Models\News;
use Illuminate\Contracts\View\Factory;

class Show
{
    /**
     * - Используется Route Model Binding
     * - Вместо глобальных функций для рендеринга используется сервис из DI-контейнера
     * - Модель прокидывается прямо в шаблон (не рекомендуется так делать)
     */
    public function __invoke(News $news, Factory $viewFactory)
    {
        return $viewFactory->make('web.admin.show', compact('news'));
    }
}