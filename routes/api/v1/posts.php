<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::
//middleware('auth')->
prefix('posts')
    ->name('posts.')
    ->group(function () {
        Route::get('', [\App\Http\Controllers\PostController::class, 'index'])->name('index');

        Route::get('/{post}', [\App\Http\Controllers\PostController::class, 'show'])->name('show');

        Route::post('', [\App\Http\Controllers\PostController::class, 'store'])->name('store');

        Route::patch('/{post}', [\App\Http\Controllers\PostController::class, 'update'])->name('update');

        Route::delete('/{post}', [\App\Http\Controllers\PostController::class, 'destroy'])->name('destroy');
    });
