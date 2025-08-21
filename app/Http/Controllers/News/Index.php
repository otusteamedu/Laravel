<?php

namespace App\Http\Controllers\News;
use App\Models\News;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Application\UseCase\IndexNewsUseCase;
class Index
{
    private $count;
    private IndexNewsUseCase $useCase;
    /**
     * Summary of __construct
     * @param \App\Application\UseCase\IndexNewsUseCase $useCase
     */
    public function __construct(IndexNewsUseCase $useCase
    ) {
        $this->useCase = $useCase;    
        $this->count = 10;
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function index(AuthManager $auth): View|RedirectResponse
    {
        $result = $this->useCase->execute(0,$this->count);
        return view('web.admin.index', ['news'=>$result['news'],'pagination'=>$result['pagination']]);
    }

    public function pagination(AuthManager $auth,$page){       
        $result = $this->useCase->execute($page,$this->count);
        if(count($result['news'])>0){
            return view('web.admin.index', ['news'=>$result['news'],'pagination'=>$result['pagination']]);
        }
        return redirect()->route('news.index');

    }
}