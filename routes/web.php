<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    Route::prefix('admin/categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
});



Route::middleware(['auth'])->group(function () {
   Route::prefix('inventory')->group(function () {
        // Hiển thị danh sách kho (GET /inventory)
        Route::get('/', [InventoryController::class, 'index']);
        
        // Hiển thị thông tin chi tiết kho (GET /inventory/{id}/show)
        Route::get('/{id}/show', [InventoryController::class, 'show']);
        
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