<?php

use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');
Route::view('notifications', 'personal.notifications');
Route::post('news/{news}/comment', [NewsCommentController::class, 'store'])->name('news.comment.store');
Route::delete('news/comment/{comment}', [NewsCommentController::class, 'destroy'])->name('news.comment.destroy');

Route::redirect('/', 'news');
