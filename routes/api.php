<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublicArticleController;
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

// -------------------- Public Routes --------------------
Route::middleware(['throttle:30,1', 'daily.limit'])->group(function () {
    // Public article listing with filters
    Route::get('/articles', [PublicArticleController::class, 'index']);
    Route::get('/articles/public/{id}', [PublicArticleController::class, 'show']);
});

// -------------------- Authentication Routes --------------------
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});

// -------------------- Protected Routes --------------------
Route::middleware(['auth:sanctum', 'throttle:60,1', 'daily.limit'])->group(function () {
    // Authenticated user info
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Articles (only for authenticated user)
    Route::get('articles/mine', [ArticleController::class, 'myArticles']);
    Route::post('articles', [ArticleController::class, 'store']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);
    Route::put('articles/{id}', [ArticleController::class, 'update']);
    Route::delete('articles/{id}', [ArticleController::class, 'destroy']);

    // Categories management
    Route::apiResource('categories', CategoryController::class);
});

