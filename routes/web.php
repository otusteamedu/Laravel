<?php

use App\Http\Controllers\News;
use Illuminate\Support\Facades\Route;


Route::prefix('news')
     ->name('news.')
     ->group(function () {
         Route::get('/', [News\Index::class,'index'])->name('index');
         Route::get('/page/{num}', [News\Index::class,'pagination'])->name('indexpage');
         Route::get('/create', [News\Create::class, 'create'])->name('create');
         Route::post('/', [News\Create::class, 'creates'])->name('store');
         Route::get('/{news}',[ News\Show::class,'show'])->name('show');
         Route::get('/{newsId}/edit', [News\Update::class, 'edit'])->name('edit');
         Route::put('/{newsId}', [News\Update::class, 'update'])->name('update');
         Route::get('/{newsId}/delete', [News\Delete::class,'delete'])->name('destroy');
     });
