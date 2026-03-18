<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Auth Routes
Route::post('/auth/register', [App\Http\Controllers\ApiAuthController::class, 'register']);
Route::post('/auth/login', [App\Http\Controllers\ApiAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [App\Http\Controllers\ApiAuthController::class, 'logout']);

// Post Routes
Route::get('/posts/latest', [App\Http\Controllers\ApiController::class, 'latestPosts']);
Route::get('/posts/top', [App\Http\Controllers\ApiController::class, 'topPosts']);
Route::get('/posts/category/{slug}', [App\Http\Controllers\ApiController::class, 'categoryPosts']);
Route::get('/post/{slug}', [App\Http\Controllers\ApiController::class, 'post']);

// Category & Settings Routes
Route::get('/categories', [App\Http\Controllers\ApiController::class, 'categories']);
Route::get('/settings', [App\Http\Controllers\ApiController::class, 'settings']);

// Citizen Journalism API
Route::post('/citizen-report', [App\Http\Controllers\ApiController::class, 'citizenReport']);
