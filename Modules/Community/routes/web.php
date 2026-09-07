<?php

use Illuminate\Support\Facades\Route;
use Modules\Community\Http\Controllers\HashtagController;
use Modules\Community\Http\Controllers\PostController;
use Modules\Community\Http\Controllers\CommentController;
use Modules\Community\Http\Controllers\LikeController;
use Modules\Community\Http\Controllers\StoryController;
use Modules\Community\Http\Controllers\FollowController;
use Modules\Community\Http\Controllers\BlockController;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['web', 'auth:admin']], function () {
    Route::patch('hashtags/{hashtag}/activate', [HashtagController::class, 'activate'])->name('hashtags.activate');
    Route::resource('hashtags', HashtagController::class)->only(['index', 'destroy']);
    
    Route::patch('posts/{post}/activate', [PostController::class, 'activate'])->name('posts.activate');
    Route::resource('posts', PostController::class)->only(['index', 'show', 'destroy']);
    
    Route::patch('comments/{comment}/activate', [CommentController::class, 'activate'])->name('comments.activate');
    Route::resource('comments', CommentController::class)->only(['index', 'show', 'destroy']);
    
    Route::resource('likes', LikeController::class)->only(['index', 'destroy']);
    
    Route::patch('stories/{story}/activate', [StoryController::class, 'activate'])->name('stories.activate');
    Route::resource('stories', StoryController::class)->only(['index', 'show', 'destroy']);
    
    Route::resource('follows', FollowController::class)->only(['index', 'destroy']);
    Route::resource('blocks', BlockController::class)->only(['index', 'destroy']);
});
