<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::middleware('guest')->group(function () {
    Route::middleware('api')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('news/{news}/comment', [NewsCommentController::class, 'store'])->name('news.comment.store');
    Route::delete('news/comment/{comment}', [NewsCommentController::class, 'destroy'])->name('news.comment.destroy');

    Route::get('cabinet', [CabinetController::class, 'index'])->name('cabinet.index');
//    Route::get('cabinet/notices', [CabinetController::class, 'notices'])
//        ->name('cabinet.notices');
    Route::put('cabinet/password', [CabinetController::class, 'updatePassword'])
        ->name('cabinet.password.update');
    Route::put('cabinet/avatar', [CabinetController::class, 'updateAvatar'])
        ->name('cabinet.avatar.update');

//    Route::get('schedules.index', [])
});

Route::redirect('/', 'news');
