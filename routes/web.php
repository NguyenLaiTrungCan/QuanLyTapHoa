<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
   Route::prefix('inventory')->group(function () {
        // Hiển thị danh sách kho (GET /inventory)
        Route::get('/', [InventoryController::class, 'index']);
        
        // Hiển thị form cập nhật kho (GET /inventory/{id}/edit)
        Route::get('/{id}/edit', [InventoryController::class, 'edit']);
        
        // Xử lý lưu dữ liệu cập nhật kho (PUT /inventory/{id})
        Route::put('/{id}', [InventoryController::class, 'update']);
    });

    Route::prefix('cart')->group(function () {
        // Xem giỏ hàng (GET /cart)
        Route::get('/', [CartController::class, 'index']);
        
        // Thêm sản phẩm vào giỏ (POST /cart/add)
        Route::post('/add', [CartController::class, 'add']);
        
        // Cập nhật số lượng sản phẩm (PUT /cart/update/{id})
        Route::put('/update/{id}', [CartController::class, 'update']);
        
        // Xóa một sản phẩm khỏi giỏ (DELETE /cart/remove/{id})
        Route::delete('/remove/{id}', [CartController::class, 'remove']);
        
        // Xóa toàn bộ giỏ hàng (DELETE /cart/clear)
        Route::delete('/clear', [CartController::class, 'clear']);
    });
});