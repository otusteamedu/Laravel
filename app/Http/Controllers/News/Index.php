<?php

namespace App\Http\Controllers\News;
use App\Models\News;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class Index
{
    private $count;
    public function __construct(
        
    ) {
        $this->count = 10;
    }

    /**
     * - Используется глобальная функция-helper view() Laravel
     */
    public function __invoke(AuthManager $auth): View|RedirectResponse
    {
        if (!$auth->check()) {
            return redirect()->route('login');
        }
        $news = News::skip(0)->take($this->count)->get();
        $count = ceil(News::count()/$this->count);
        $pagination = ['count'=>$count,'page'=>0];
        return view('web.admin.index', ['news'=>$news,'pagination'=>$pagination]);
    }

    public function pagination(AuthManager $auth,$page){
        if (!$auth->check()) {
            return redirect()->route('login');
        }
        $offset = (int)$page*$this->count;
        $count = ceil(News::count()/$this->count);
        $news = News::skip($offset)->take($this->count)->get();
        if(count($news)>0){
            $pagination = ['count'=>$count,'page'=>$page];
            return view('web.admin.index', ['news'=>$news,'pagination'=>$pagination]);
        }
        return redirect()->route('news.index');

    }
}