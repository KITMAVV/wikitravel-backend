<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/pages', [PageController::class, 'index']);
Route::get('/pages/slug/{slug}', [PageController::class, 'showBySlug']);
Route::get('/pages/{id}', [PageController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::post('/pages', [PageController::class, 'store']);
    Route::put('/pages/{id}', [PageController::class, 'update']);
    Route::delete('/pages/{id}', [PageController::class, 'destroy']);
    Route::get('/pages/{id}/revisions', [PageController::class, 'revisions']);
    Route::post('/pages/{id}/restore/{rev}', [PageController::class, 'restore']);

    Route::post('/media', [MediaController::class, 'store']);
});

Route::get('/media', [MediaController::class, 'index']);
Route::get('/search', [PageController::class, 'search']);
