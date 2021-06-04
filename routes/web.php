<?php

use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::view('cabinet/notifications', 'cabinet.notifications')->name('cabinet.notifications');
Route::view('cabinet', 'cabinet.index')->name('cabinet.index');

Route::put('cabinet/password', function () {
    // todo updating password
})->name('cabinet.password.update');

Route::post('news/{news}/comment', [NewsCommentController::class, 'store'])->name('news.comment.store');
Route::delete('news/comment/{comment}', [NewsCommentController::class, 'destroy'])->name('news.comment.destroy');

Route::redirect('/', 'news');
