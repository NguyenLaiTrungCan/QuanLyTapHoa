<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

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


// MODULE: KHO HÀNG (Dành cho Admin - có thể thêm middleware admin sau)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::get('/inventory/{id}', [InventoryController::class, 'show']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);
});

// MODULE: GIỎ HÀNG (Dành cho Customer đã đăng nhập)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'add']);
    Route::put('/cart/update/{id}', [CartController::class, 'update']); // Cập nhật số lượng
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove']); // Xóa 1 item
    Route::delete('/cart/clear', [CartController::class, 'clear']); // Xóa toàn bộ giỏ
});