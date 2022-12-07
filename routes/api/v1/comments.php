<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')
    ->prefix('comments')
    ->name('comments.')
    ->group(function () {
        Route::get('', [\App\Http\Controllers\CommentController::class, 'index'])->name('index');

        Route::get('/{comment}', [\App\Http\Controllers\CommentController::class, 'show'])->name('show');

        Route::post('', [\App\Http\Controllers\CommentController::class, 'store'])->name('store');

        Route::patch('/{comment}', [\App\Http\Controllers\CommentController::class, 'update'])->name('update');

        Route::delete('/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('destroy');
    });
