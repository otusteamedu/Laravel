<?php

namespace App\Http\Controllers\News;

use App\Http\Requests\UpdateNewsRequest;
use Illuminate\Contracts\View\View;
use App\Models\News;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Application\UseCase\UpdateNewsUseCase;
class Update
{
    private UpdateNewsUseCase $useCase;
    /**
     * Summary of __construct
     * @param \App\Application\UseCase\UpdateNewsUseCase $useCase
     */
    public function __construct(UpdateNewsUseCase $useCase
    ) {
        $this->useCase = $useCase;
    }

    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(AuthManager $auth,int $newsId): View
    {
        $news = News::query()->find($newsId);

        if (!$news) {
            throw new NotFoundHttpException();
        }

        return view('web.admin.edit', [
            'newsId'    => $news->id,
            'name'     => $news->name,
            'text'      => $news->text,
            'photo'  => $news->photo,
            'userId'  => $news->user_id,
        ]);  
    }

    public function update(AuthManager $auth,UpdateNewsRequest $request,int $newsId): RedirectResponse
    {
        $requestData = $request->validated();
        $this->useCase->execute($requestData,$newsId);
        return redirect()->route('news.edit', ['newsId' => $newsId]);
    }
}