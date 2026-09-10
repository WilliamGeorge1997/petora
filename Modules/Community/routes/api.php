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
Route::get('stories/{story_id}', [StoryController::class, 'show']);
Route::get('clients/{client_id}/stories', [StoryController::class, 'clientStories']);
Route::apiResource('stories', StoryController::class)->except(['update', 'show']);

// Comments
Route::get('posts/{post}/comments', [CommentController::class, 'index']);
Route::post('posts/{post}/comments', [CommentController::class, 'store']);
Route::get('comments/{comment}/replies', [CommentController::class, 'replies']);
Route::post('comments/{comment}/replies', [CommentController::class, 'reply']);
Route::post('comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

// Likes
Route::get('posts/{post_id}/likes', [LikeController::class, 'index']);
Route::post('posts/{post_id}/like', [LikeController::class, 'toggle']);
Route::get('comments/{comment_id}/likes', [LikeController::class, 'index']);
Route::post('comments/{comment_id}/like', [LikeController::class, 'toggle']);
Route::get('stories/{story_id}/likes', [LikeController::class, 'index']);
Route::post('stories/{story_id}/like', [LikeController::class, 'toggle']);

// Follows
Route::get('clients/{client_id}/followers', [FollowController::class, 'followers']);
Route::get('clients/{client_id}/following', [FollowController::class, 'following']);
Route::post('clients/{client_id}/follow', [FollowController::class, 'toggle']);

// Blocks
Route::get('blocks', [BlockController::class, 'index']);
Route::post('clients/{client_id}/block', [BlockController::class, 'toggle']);

// Hashtags
Route::get('hashtags/{hashtag_id}', [HashtagController::class, 'show']);
Route::apiResource('hashtags', HashtagController::class)->only(['index']);
