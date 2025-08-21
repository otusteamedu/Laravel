<?php

namespace App\Http\Controllers\News;

use Illuminate\Auth\AuthManager;
use App\Application\UseCase\DeleteNewsUseCase;
class Delete
{
    private DeleteNewsUseCase $useCase;
    /**
     * Summary of __construct
     * @param \App\Application\UseCase\DeleteNewsUseCase $useCase
     */
    public function __construct(DeleteNewsUseCase $useCase
    ) {
        $this->useCase = $useCase;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete($newsId,AuthManager $auth)
    {
        
        $this->useCase->execute($newsId);
        return redirect()->route('news.index')
            ->with('success', 'Пост успешно удален');
    }
}