<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIK
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/post/{slug}', [PostController::class, 'show'])->name('posts.show');

// ============================================================
// AUTH (custom dari nol: bcrypt + session)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============================================================
// ADMIN (dilindungi middleware auth)
// ============================================================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('posts', AdminPostController::class)->except(['show']);
    Route::patch('posts/{post}/toggle-status', [AdminPostController::class, 'toggleStatus'])
        ->name('posts.toggle-status');

    Route::resource('categories', CategoryController::class)
        ->except(['show']);
});
