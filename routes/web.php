<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WithdrawController;
use App\Models\Post;
use App\Models\PostPreview;
use App\Models\User;
use App\Queries\UserQueries;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\returnArgument;

Route::get('/', function () {
    return Cache::remember('main-page:' . Auth::id(), 10, fn() => view('welcome')->render());
});

Route::view('/page', 'page');

Route::resource('posts', PostController::class)->middleware('auth');

Route::get('/dashboard', function (Request $request) {
    $locale = mb_substr($request->headers->get('accept-language'), 0, 2);
    App::setLocale($locale);

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/qb', function (Request $request) {
    $res = \App\Repo\UserRepo::getTopUsers();

    dump($res);

    return $res;
});

Route::get('/withdraw', [WithdrawController::class, 'withdraw'])->name('withdraw');

Route::get('/c', function (Request $request) {
    $users = User::all();

    dump($users->map(fn($v) => $v->name));
    dump($users->map->name);
    dump($users->filter(fn($u) => $u->is_admin));
    dump($users->filter->is_admin->map->name);

    return "ok";
});

Route::get('/lazy', function () {
    $users = User::lazy();

    dump($users->all());

    return "ok";
});

Route::get('/file', function () {
    $text = Storage::exists('sub/text.txt');
    return dump($text);
});

Route::get('/log', function (Request $request) {
    $qwe = 123;
    Log::channel('syslog')->info('get info request');
    Log::channel('syslog')->warning('get warn request');
    Log::channel('syslog')->emergency('get warn request');

    return ['ok' => true];
});

Route::post('/upload', function (Request $request) {
    $file = $request->file('avatar');

    $res = Storage::putFileAs(
        'avatars',
        $file,
        'new_name.' . $file->getClientOriginalExtension()
    );

    dump($res);

    return "ok";
})->name('upload');

Route::get('/download', function () {
    $filename = 'avatars/new_name.png';
    // abort(404);
    return Storage::download($filename, 'скачай меня.png');
});

Route::get('/download/url', function () {
    $filename = 'avatars/new_name.png';
    // abort(404);
    return Storage::disk('public')->url($filename);
});

Route::group(['prefix' => '/e'], function () {
    Route::get('/create', function (Request $request) {
        // $post = new Post();
        // $post->title = "new post";
        // $post->text = "new text";
        // $post->is_draft = true;
        // $post->author_id = 2;

        dump(Post::create([
            "title" => "2new post",
            "text" => "2new text",
            "is_draft" => false,
            "author_id" => 2,
        ]));

        // dump($post->save());

        return "";
    });

    Route::get("/update", function () {
        $post = Post::find(1);
        $post->title = "12updated title";

        dump($post->save());

        return "";
    });

    Route::get("/delete", function () {
        $post = Post::withTrashed()->find(2);

        dump($post->trashed());

        dump($post->restore());

        return "";
    });

    Route::get('/one', function () {
        $post = Post::find(3);

        // $preview = new PostPreview();
        // $preview->photo_url = 'qwe';

        // $post->preview()->save($preview);

        dump($post);
        dump($post->preview->post);

        return "";
    });

    Route::get('/one-all', function () {
        $posts = Post::all();

        $posts->load('preview');


        // $preview = new PostPreview();
        // $preview->photo_url = 'qwe';

        // $post->preview()->save($preview);

        dump($posts);

        foreach ($posts as $post) {
            $post->preview;
        }

        return "";
    });

    Route::get("/poly", function () {
        Post::find(1);
    });
});

Route::group(['prefix' => '/cache'], function () {
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
        Cache::tags(['people', 'admins'])->set('current_admin', 'John');
        Cache::tags(['people', 'users'])->set('current_user', 'Kate');

        return "ok";
    });

    Route::get('/get-tags', function () {
        return Cache::tags(['people', 'admins'])->get('current_admin', 'anon');
    });
    Route::get('/flush-tags', function () {
        return Cache::tags(['admins'])->flush();
    });

    Route::get('/all-posts', function () {
        $posts = Post::remember(now()->add(20, 'seconds'), 'my-posts')
            ->cacheTags(['posts'])
            ->get();

        dump($posts[0]->title);
        return "";
    });

    Route::get('/all-posts2', function () {
        $posts = Post::remember(now()->add(20, 'seconds'))
            ->cacheTags(['posts'])
            ->get();

        dump($posts[0]->title);
        return "";
    });

    Route::get('/all-posts-flush', function () {
        Post::flushCache('posts');

        return "ok";
    });
});

require __DIR__ . '/auth.php';
