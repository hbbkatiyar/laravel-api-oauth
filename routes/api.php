<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\Provider\MessageController;
use App\Http\Controllers\Provider\EmailController;

Route::get('/', function () {
    return response()->json(['message' => "It is working fine"], 200);
})->name('home.page');

Route::post('register', [UserController::class, 'register'])->name('users.register');
Route::post('login', [UserController::class, 'login'])->name('users.login');

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile'])->name('users.profile');

    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{id}', [CommentController::class, 'show'])->name('comments.show');

    Route::get('/send-message', [MessageController::class, 'create'])->name('send-message.index');
    Route::post('/send-message', [MessageController::class, 'store'])->name('send-message.store');

    Route::post('/send-email', [EmailController::class, 'sendEmail'])->name('send-email.store');
    
});
