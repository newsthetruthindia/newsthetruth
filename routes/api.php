<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/login', [ApiAuthController::class, 'login']);
Route::post('/auth/forgot-password', [ApiAuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [ApiAuthController::class, 'resetPassword']);
Route::middleware('auth:sanctum')->post('/auth/logout', [ApiAuthController::class, 'logout']);

// Post & User Routes
Route::get('/user/{id}', [ApiController::class, 'user']);
Route::get('/posts/user/{id}', [ApiController::class, 'userPosts']);
Route::get('/posts/latest', [ApiController::class, 'latestPosts']);

// Personalized feed & preferences (Auth required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/preferences', [ApiController::class, 'getPreferences']);
    Route::post('/user/preferences', [ApiController::class, 'savePreferences']);
    Route::get('/posts/feed', [ApiController::class, 'personalizedFeed']);
    Route::post('/user/push-token', [ApiController::class, 'updatePushToken']);
    Route::post('/posts/{id}/comments', [\App\Http\Controllers\InteractionController::class, 'addComment']);
    Route::post('/posts/{id}/react', [\App\Http\Controllers\InteractionController::class, 'react']);
});
Route::post('/polls/{id}/vote', [\App\Http\Controllers\PollController::class, 'vote']);
Route::get('/polls/active', [\App\Http\Controllers\PollController::class, 'getActivePoll']);
Route::get('/polls/{id}', [\App\Http\Controllers\PollController::class, 'getPoll']);
Route::get('/posts/{id}/comments', [\App\Http\Controllers\InteractionController::class, 'getComments']);
Route::get('/posts/top', [ApiController::class, 'topPosts']);
Route::get('/posts/category/{slug}', [ApiController::class, 'categoryPosts']);
Route::get('/post/{slug}', [ApiController::class, 'post']);
Route::post('/posts/track', [ApiController::class, 'track']);
Route::get('/posts/search', [ApiController::class, 'searchPosts']);
Route::get('/posts/archive', [ApiController::class, 'archivePosts']);

// Category & Settings Routes
Route::get('/categories', [ApiController::class, 'categories']);
Route::get('/settings', [ApiController::class, 'settings']);

// Tags & Videos
Route::get('/tags', [ApiController::class, 'tags']);
Route::get('/videos', [ApiController::class, 'videos']);

// Citizen Journalism API
Route::post('/citizen-report', [ApiController::class, 'citizenReport']);

// Sponsor Ads
Route::get('/sponsors', [\App\Http\Controllers\Api\SponsorController::class, 'index']);
Route::get('/sponsor/{type?}', [\App\Http\Controllers\Api\SponsorController::class, 'getRandom']);

Route::get('/reporters', [ApiController::class, 'activeReporters']);

// RSS Feed Proxy Routes
Route::get('/feed/news', [\App\Http\Controllers\Api\NewsFeedController::class, 'rss']);
