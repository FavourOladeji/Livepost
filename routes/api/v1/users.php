<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')
    ->prefix('users')
    ->name('users.')
    ->group(function () {
        Route::get('', [\App\Http\Controllers\UserController::class, 'index'])->name('index');

        Route::get('/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('show');

        Route::post('', [\App\Http\Controllers\UserController::class, 'store'])->name('store');

        Route::patch('/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('update');

        Route::delete('/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });
