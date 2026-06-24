<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    if (! Schema::hasTable('categories') || ! Schema::hasTable('products') || ! Schema::hasTable('inventories')) {
        return view('welcome', [
            'categories' => collect(),
            'latestProducts' => collect(),
            'bestSellingProducts' => collect(),
        ]);
    }

    $categories = Category::withCount('products')
        ->orderByDesc('products_count')
        ->orderBy('name')
        ->limit(6)
        ->get();

    $latestProducts = Product::with(['category', 'inventory'])
        ->whereHas('inventory', fn ($query) => $query->where('quantity', '>', 0))
        ->latest()
        ->limit(6)
        ->get();

    $bestSellingProducts = Schema::hasTable('order_items')
        ? Product::with(['category', 'inventory'])
            ->withSum('orderItems as total_sold', 'quantity')
            ->whereHas('inventory', fn ($query) => $query->where('quantity', '>', 0))
            ->orderByDesc('total_sold')
            ->latest('products.id')
            ->limit(4)
            ->get()
        : collect();

    if ($bestSellingProducts->every(fn ($product) => (int) $product->total_sold === 0)) {
        $bestSellingProducts = Product::with(['category', 'inventory'])
            ->whereHas('inventory', fn ($query) => $query->where('quantity', '>', 0))
            ->latest()
            ->limit(4)
            ->get();
    }

    return view('welcome', compact('categories', 'latestProducts', 'bestSellingProducts'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login')->name('login.store');
});

// Products Public Catalog
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    // Orders (Customer)
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/checkout-selected', [CartController::class, 'checkoutSelected'])->name('checkout.selected');
    });

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Admin Group
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Admin Products
        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');

        // Admin Categories (renamed to admin.categories for consistency)
        Route::resource('categories', CategoryController::class);

        // Admin Inventory
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [InventoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InventoryController::class, 'update'])->name('update');
        });

        // Admin Order status updates
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});
