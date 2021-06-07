<?php

use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\LessonController;
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
//    Route::get('cabinet/notices', [CabinetController::class, 'notices'])->name('cabinet.notices');
    Route::put('cabinet/password', [CabinetController::class, 'updatePassword'])->name('cabinet.password.update');
    Route::put('cabinet/avatar', [CabinetController::class, 'updateAvatar'])->name('cabinet.avatar.update');

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
            Route::put('schedules/lessons/{group}', [AdminScheduleController::class, 'updateLessonSchedule'])
                ->name('schedules.lessons.update');
        });
    });

    Route::middleware('roles:teacher')->group(function () {
        Route::get('disciplines/teacher', [DisciplineController::class, 'teacherIndex'])->name('disciplines.teacher.index');
        Route::get('disciplines/teacher/{discipline}', [DisciplineController::class, 'teacherShow'])->name('disciplines.teacher.show');

        Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::delete('homeworks/{homework}', [HomeworkController::class, 'destroy'])->name('homeworks.destroy');
        Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
        Route::get('homeworks/{homework}/edit', [HomeworkController::class, 'edit'])->name('homeworks.edit');
        Route::put('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::put('homeworks/{homework}', [HomeworkController::class, 'update'])->name('homeworks.update');

        Route::get('lessons/create/{discipline}', [LessonController::class, 'create'])->name('lessons.create');
        Route::get('homeworks/create/{discipline}', [HomeworkController::class, 'create'])->name('homeworks.create');
        Route::post('lessons/{discipline}', [LessonController::class, 'store'])->name('lessons.store');
        Route::post('homeworks/{discipline}', [HomeworkController::class, 'store'])->name('homeworks.store');
        Route::put('grades', GradeController::class)->name('grade.update-create');
    });

    Route::middleware('roles:student')->group(function () {
        Route::get('disciplines/student', [DisciplineController::class, 'studentIndex'])->name('disciplines.student.index');
        Route::get('disciplines/student/{discipline}', [DisciplineController::class, 'studentShow'])->name('disciplines.student.show');
    });

    Route::middleware('roles:teacher,student')->group(function () {
        Route::get('lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::get('homeworks/{homework}', [HomeworkController::class, 'show'])->name('homeworks.show');
    });
});

Route::redirect('/', 'news');
