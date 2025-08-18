<?php

use Illuminate\Foundation\Inspiring;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command("app:echo {text}", function () {
    $this->line($this->argument('text'));
})->purpose("Just echo");


