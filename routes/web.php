<?php

use App\Http\Controllers\PostController;
use App\Jobs\SendEmail;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $suffix = Auth::user() ? '.user' : '.guest';

    return Cache::remember('homePage' . $suffix, 20, fn() => view('welcome')->render());

})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::apiResource('posts', PostController::class);

Route::get('/log', function (Request $request) {
    $amount = '200.00';
    $context = ['req' => $request, 'amount' => $amount];
    Log::info('Log message', $context);
    Log::debug('Amount', compact('amount'));

    return "ok";
});

Route::get('/collect', function (Request $request) {
    $users = User::all();

    dump($users
        ->filter(fn($user) => $user->is_admin)
        ->map(fn($user) => $user->name));

    dump($users->filter->is_admin->map->name);

    return "ok";
});

Route::get('/lc', function () {
    $users = User::cursor();

    $admins = $users->filter->is_admin;

    foreach ($admins as $admin) {
        echo '' . $admin->name . '<br>';
    }

    return 'ok';
});

Route::get('/create-html', function () {
    Storage::disk('public')->put('test.html', '<h1>file</h1>');

    return 'ok';
});

Route::get('/private-link', function () {
    Storage::put('private.html', '<h1>private</h1>');

    $url = Storage::temporaryUrl('private.html', now()->addMinutes(5));

    return $url;
});

Route::post('/upload-file', function () {
    $file = request()->file('image');
    $name = $file->getClientOriginalPath();
    $fileName = $file->storeAs('images', $name, 'public');

    return Storage::disk('local')->temporaryUrl($fileName, now()->addMinutes(5));

})->name('upload-file');

require __DIR__ . '/settings.php';

Route::get('/cache/get', function () {
    $value = Cache::remember('number', 5, function () {
        \Debugbar::info('from code');
        return '123';
    });

    return $value;
});

Route::get('/lock', function () {
    try {
        $res = Cache::lock('op_name', 30)->block(2, function () {
            sleep(5);
            return true;
        });

        return 'захватили блокировку';
    } catch (Illuminate\Contracts\Cache\LockTimeoutException $e) {
        return 'НЕ захватили блокировку';
    }
});

Route::get('/dispatch', function () {
    $job = new SendEmail("to@example.com", "Testing queue", "Email body");

    dispatch($job);


    // SendEmail::dispatch("to@example.com", "Testing queue", "Email body");

    // dispatch(function () {
    //     \Log::info("dispatch closure");
    // });

    return "ok";
});

Route::get('/cache-tags', function () {
    Cache::tags(['posts', 'articles'])->put('posts', 'from db');


    return Cache::tags(['posts', 'articles'])->get('posts', 'none');
});
