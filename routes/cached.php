<?
use Illuminate\Support\Facades\Cache;
use App\Models\News;
use App\Models\User;
use App\Services\Memcached;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/cached'], function () {
    Route::get('/get', function () {
        // $cachedUsers = Cache::get('users');

        // if (is_null($cachedUsers)) {
        //     $cachedUsers = $users = User::all();
        //     Cache::set('users', $users, 10);
        // }


        $cachedUsers = Cache::remember('users', 10, function () {
            return User::all();
        });

        return $cachedUsers;

        // return Cache::remember("name", 70, fn() => "rember");
        // return Cache::get('name', 'anon');
    });

    Route::get('/set', function () {
        return Cache::set('name', 'Kate', 5);
    });

    Route::get('lock', function () {
        $lock = Cache::lock("monthly_report_generation", 10);

        try {
            $lock->block(3);
            dump($lock);
            sleep(7);
            $lock->release();
            return "ok";
        } catch (LockTimeoutException $e) {
            return "locked";
        }
    });

    Route::get('/optional', function () {
        $obj = null;
        dump(optional($obj)->name());
        return '';
    });

    Route::get('/set-tags', function () {
        $json = Memcached::set(['admins'=>['current_admin'=> 'John'],'users'=>['current_user'=>'Kate']]);
        Cache::set('people',$json,5);
        return "ok";
    });

    Route::get('/get-tags', function () {
        $json = Cache::get('people');
        $arr = Memcached::get($json);
        dump($arr);
    });
    Route::get('/flush-tags', function () {
        return  Memcached::delete('people','admins');
    });

    Route::get('/news-set', function () {
        $news = Cache::remember('all_news', 60, function () {
            return News::all();
        });
        return $news;
    });

    // Route::get('/news-get', function () {
    //     $news = Cache::get('all_news');
    //     return $news;
    // });

    // Route::get('/all-news-flush', function () {
    //     Cache::forget('all_news');
    //     return "ok";
    // });

    Route::get('/news-get', function () {
        $news = News::remember(now()->add(20, 'seconds'))
            ->cacheTags(['news'])
            ->get();

        dump($news[0]->name);
        return "";
    });

    Route::get('/all-news-flush', function () {
        News::flushCache('news');
        return "ok";
    });
});