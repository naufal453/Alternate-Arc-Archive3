<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChapterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', [PostController::class, 'index'])->name('home.index');

// Guest middleware routes
Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register.show');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.perform');

    Route::get('/login', [LoginController::class, 'show'])->name('login.show');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
});

// Auth middleware routes
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');

    // Posts (excluding index & show since they are defined separately)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// Post detail routes
Route::get('/post/{id}', [PostController::class, 'show'])->name('home.post.detail');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

// Comments
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/posts/{id}/comments', [CommentController::class, 'show'])->name('posts.comments');

// Chapters (using resource routes properly)
Route::resource('chapters', ChapterController::class)->except(['create', 'edit']);
