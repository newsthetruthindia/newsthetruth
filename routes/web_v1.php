<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\PublicPageController;

Route::get('/', [PublicPageController::class, 'index']);
Route::get('/about', [PublicPageController::class, 'about']);
Route::get('/contact', [PublicPageController::class, 'contact']);
