<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;

// AUTH
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);
});

// PAGES (публічні)
Route::get('/pages',              [PageController::class, 'index']);
Route::get('/pages/{id}',         [PageController::class, 'show']);
Route::get('/pages/slug/{slug}',  [PageController::class, 'showBySlug']);

// PAGES (приватні)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/pages',               [PageController::class, 'store']);
    Route::put('/pages/{id}',           [PageController::class, 'update']);
    Route::delete('/pages/{id}',        [PageController::class, 'destroy']);
    Route::get('/pages/{id}/revisions', [PageController::class, 'revisions']);
});

// MEDIA
Route::get('/media',  [MediaController::class, 'index']);
Route::post('/media', [MediaController::class, 'store'])->middleware('auth:sanctum');

// SEARCH
Route::get('/search', [PageController::class, 'search']);