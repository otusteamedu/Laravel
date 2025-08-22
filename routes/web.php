<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

require __DIR__.'/auth.php';

Route::get('/redirect', function(Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => '1', // Replace with valid client id
        'redirect_uri' => 'http://laravel/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
        // 'prompt' => '', // "none", "consent", or "login"
    ]);

    return redirect('http://laravel/oauth/authorize?' . $query);
});

Route::get('/callback', function(Request $request) {
    $state = $request->session()->pull('state');
    throw_unless(
        strlen($state) > 0 && $state === $request->state,
        InvalidArgumentException::class,
        'Invalid state value.'
    );

    $response = Http::asForm()->post('http://nginx/oauth/token', [
        'grant_type' => 'authorization_code',
        'client_id' => '1', // Replace with valid client id
        'client_secret' => 'a89wGBQucrWcGYqtxXiJhmjf7ORk6Kilu4AWcgWI', // Replace with client secret
        'redirect_uri' => 'http://laravel/auth/callback',
        'code' => $request->code
    ]);

    return $response->json();
});
