<?php

use Illuminate\Support\Facades\Route;
use Modules\Community\Http\Controllers\Api\BlockController;
use Modules\Community\Http\Controllers\Api\CommentController;
use Modules\Community\Http\Controllers\Api\FollowController;
use Modules\Community\Http\Controllers\Api\HashtagController;
use Modules\Community\Http\Controllers\Api\LikeController;
use Modules\Community\Http\Controllers\Api\PostController;
use Modules\Community\Http\Controllers\Api\StoryController;

    // Posts
    Route::get('posts/{post_id}', [PostController::class, 'show']);
    Route::post('posts/{post}', [PostController::class, 'update']);
    Route::apiResource('posts', PostController::class)->except(['show', 'update']);

    // Stories
    Route::apiResource('stories', StoryController::class)->except(['update', 'show']);
    
    // Comments
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::apiResource('comments', CommentController::class)->only(['update', 'destroy']);

    // Likes
    Route::get('posts/{post_id}/likes', [LikeController::class, 'index']);
    Route::post('posts/{post_id}/like', [LikeController::class, 'toggle']);
    Route::get('comments/{comment_id}/likes', [LikeController::class, 'index']);
    Route::post('comments/{comment_id}/like', [LikeController::class, 'toggle']);

    // Follows
    Route::get('users/{user_id}/followers', [FollowController::class, 'followers']);
    Route::get('users/{user_id}/following', [FollowController::class, 'following']);
    Route::post('users/{user_id}/follow', [FollowController::class, 'toggle']);

    // Blocks
    Route::get('blocks', [BlockController::class, 'index']);
    Route::post('users/{user_id}/block', [BlockController::class, 'toggle']);

    // Hashtags
    Route::get('hashtags/{hashtag_id}', [HashtagController::class, 'show']);
    Route::apiResource('hashtags', HashtagController::class)->only(['index']);
