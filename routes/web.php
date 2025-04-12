<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostManagementController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SearchController;

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
Route::get('/test-image', function() {
    $path = 'profile_pictures/1744415868_ChatGPT Image Apr 12 2025 04_00_00 AM (1).png';
    return redirect(asset(str_replace(' ', '%20', 'storage/'.$path)));
});

Route::get('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
    Route::get('/user/{username}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{username}/edit', [UserController::class, 'edit'])->name('user.usersettings');
    Route::patch('/user/{username}/update', [UserController::class, 'update'])->name('users.update');
    Route::post('/user/change-email', [UserController::class, 'changeEmail'])->name('users.change-email');

    // Posts resource with proper auth protection
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    
    // Post management routes
    Route::post('/posts/{id}/save', [PostManagementController::class, 'savePost'])->name('posts.save');
    Route::post('/posts/{id}/unsave', [PostManagementController::class, 'unsavePost'])->name('posts.unsave');
    Route::post('/posts/{id}/archive', [PostManagementController::class, 'archivePost'])->name('posts.archive');
    Route::post('/posts/{id}/unarchive', [PostManagementController::class, 'unarchivePost'])->name('posts.unarchive');
    Route::get('/saved', [PostManagementController::class, 'savedPosts'])->name('posts.saved');
    Route::get('/archived', [PostManagementController::class, 'archivedPosts'])->name('posts.archived');
});

// Public post routes
Route::get('/post/{id}', [PostController::class, 'show'])->name('home.post.detail');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

// Comments
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
Route::get('/posts/{id}/comments', [CommentController::class, 'show'])->name('posts.comments');

// Likes
Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
Route::delete('/likes', [LikeController::class, 'destroy'])->name('likes.destroy');

// Chapters
Route::resource('chapters', ChapterController::class)->except(['create', 'edit']);

// Search
Route::get('/search', [SearchController::class, 'search'])->name('search.results');
