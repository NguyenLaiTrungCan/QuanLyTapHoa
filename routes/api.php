<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

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