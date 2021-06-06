<?php

use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\NewsCommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class, 'index'])->name('news.index');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('schedules', [ScheduleController::class, 'index'])->name('schedules.index');

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

    Route::middleware('roles:admin,department head')->group(function () {
        Route::get('admin', [AdminController::class, 'index'])->name('admin.index');

        Route::prefix('admin')->group(function () {
            Route::resource('students', StudentController::class)->except(['index', 'show']);

            Route::resource('teachers', TeacherController::class)->except(['index', 'show']);

            Route::resource('disciplines', DisciplineController::class)->except(['index', 'show']);

            Route::resource('news', AdminNewsController::class)->except(['index', 'show']);

            Route::resource('groups', GroupController::class)->except(['index', 'show']);

            Route::get('schedules/lessons/{group}/edit', [AdminScheduleController::class, 'edit'])
                ->name('schedules.lessons.edit');
            Route::put('schedules/lessons/{group}', [AdminScheduleController::class, 'update'])
                ->name('schedules.lessons.update');
        });
    });
});

Route::redirect('/', 'news');
