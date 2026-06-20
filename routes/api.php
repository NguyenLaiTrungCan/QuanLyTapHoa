<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryApiController::class, 'index']);
    Route::get('/{category}', [CategoryApiController::class, 'show']);

    Route::middleware('auth')->group(function () {
        Route::post('/', [CategoryApiController::class, 'store']);
        Route::put('/{category}', [CategoryApiController::class, 'update']);
        Route::delete('/{category}', [CategoryApiController::class, 'destroy']);
    });
});

Route::apiResource('products', ProductController::class)->names('api.products');
