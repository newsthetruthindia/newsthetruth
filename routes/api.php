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
Route::post('/auth/verify-email', [ApiAuthController::class, 'verifyEmail']);
Route::middleware('auth:sanctum')->post('/auth/logout', [ApiAuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/auth/resend-verification', [ApiAuthController::class, 'resendVerification']);

// Post & User Routes
Route::get('/user/{id}', [ApiController::class, 'user']);
Route::get('/posts/user/{id}', [ApiController::class, 'userPosts']);
Route::get('/posts/latest', [ApiController::class, 'latestPosts']);
Route::get('/posts/top', [ApiController::class, 'topPosts']);
Route::get('/posts/category/{slug}', [ApiController::class, 'categoryPosts']);
Route::get('/post/{slug}', [ApiController::class, 'post']);
Route::get('/posts/search', [ApiController::class, 'searchPosts']);
Route::get('/posts/archive', [ApiController::class, 'archivePosts']);
Route::get('/archive/stats', [ApiController::class, 'archiveSummary']);
Route::get('/reporters', [ApiController::class, 'activeReporters']);

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

// Public News Monitor (for TV)
Route::get('/v1/monitor/{key}', [\App\Http\Controllers\Api\PublicMonitorController::class, 'show']);

// Newsletter Subscriptions
Route::post('/v1/subscribe', [\App\Http\Controllers\Api\SubscriberController::class, 'store']);
