<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Manager\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskFileController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('tasks.comments.store');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])
    ->name('comments.update');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->name('comments.destroy');

    Route::post('/tasks/{task}/files', [TaskFileController::class, 'store'])
    ->name('tasks.files.store');

    Route::delete('/files/{file}', [TaskFileController::class, 'destroy'])
    ->name('tasks.files.destroy');


});

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class,'index'])->name('dashboard');
        Route::resource('departments', DepartmentController::class);
        Route::resource('users', UserController::class);
    });

    Route::middleware('role:manager')->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ManagerDashboard::class,'index'])->name('dashboard');
        Route::resource('tasks', TaskController::class);
    });

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboard::class,'index'])->name('dashboard');
        Route::resource('tasks', \App\Http\Controllers\Staff\TaskController::class)->only(['index', 'show']);
    });


    Route::get('/notifications', function () {
    Auth::user()->unreadNotifications->markAsRead();

    return view('notifications.index', [
        'notifications' => Auth::user()->notifications
    ]);
})->name('notifications');

});

require __DIR__.'/auth.php';
