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

// Post Routes
Route::get('/posts/latest', [App\Http\Controllers\ApiController::class, 'latestPosts']);
Route::get('/posts/top', [App\Http\Controllers\ApiController::class, 'topPosts']);
Route::get('/posts/category/{slug}', [App\Http\Controllers\ApiController::class, 'categoryPosts']);
Route::get('/post/{slug}', [App\Http\Controllers\ApiController::class, 'post']);

// Category & Settings Routes
Route::get('/categories', [App\Http\Controllers\ApiController::class, 'categories']);
Route::get('/settings', [App\Http\Controllers\ApiController::class, 'settings']);
