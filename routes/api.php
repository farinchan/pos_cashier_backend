<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\SpendingController;
use App\Http\Controllers\API\ReportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'v1'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store'])->middleware('auth:sanctum');
        Route::put('/{id}', [ProductController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');

        Route::post('/search', [ProductController::class, 'search']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store'])->middleware('auth:sanctum');
        Route::put('/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware('auth:sanctum');
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::post('/', [TransactionController::class, 'store'])->middleware('auth:sanctum');
        Route::put('/{id}', [TransactionController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{id}', [TransactionController::class, 'destroy'])->middleware('auth:sanctum');
    });

    Route::prefix('spendings')->group(function () {
        Route::get('/', [SpendingController::class, 'index']);
        Route::get('/{id}', [SpendingController::class, 'show']);
        Route::post('/', [SpendingController::class, 'store'])->middleware('auth:sanctum');
        Route::put('/{id}', [SpendingController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/{id}', [SpendingController::class, 'destroy'])->middleware('auth:sanctum');
    });

    Route::prefix('reports')->group(function () {
        Route::get('/all', [ReportController::class, 'all']);
    });

});
