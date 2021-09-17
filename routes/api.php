<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/all', [CategoryController::class, 'all']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::post('/{category}/update', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('authors')->group(function () {
        Route::get('/', [AuthorController::class, 'index']);
        Route::get('/all', [AuthorController::class, 'all']);
        Route::post('/store', [AuthorController::class, 'store']);
        Route::post('/{author}/update', [AuthorController::class, 'update']);
        Route::delete('/{author}', [AuthorController::class, 'destroy']);
    });

    Route::prefix('books')->group(function () {
        Route::get('/', [BookController::class, 'index']);
        Route::get('/all', [BookController::class, 'all']);
        Route::post('/store', [BookController::class, 'store']);
        Route::post('/{book}/update', [BookController::class, 'update']);
        Route::delete('/{book}', [BookController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/all', [BookController::class, 'all']);
        Route::post('/store', [BookController::class, 'store']);
        Route::post('/{book}/update', [BookController::class, 'update']);
        Route::delete('/{book}', [BookController::class, 'destroy']);
    });
});


