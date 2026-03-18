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
Route::middleware('auth:sanctum')->post('/auth/logout', [ApiAuthController::class, 'logout']);

// Post Routes
Route::get('/posts/latest', [ApiController::class, 'latestPosts']);
Route::get('/posts/top', [ApiController::class, 'topPosts']);
Route::get('/posts/category/{slug}', [ApiController::class, 'categoryPosts']);
Route::get('/post/{slug}', [ApiController::class, 'post']);
Route::get('/posts/search', [ApiController::class, 'searchPosts']);

// Category & Settings Routes
Route::get('/categories', [ApiController::class, 'categories']);
Route::get('/settings', [ApiController::class, 'settings']);

// Tags & Videos
Route::get('/tags', [ApiController::class, 'tags']);
Route::get('/videos', [ApiController::class, 'videos']);

// Citizen Journalism API
Route::post('/citizen-report', [ApiController::class, 'citizenReport']);
