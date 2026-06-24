<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Inventory;
use App\Http\Requests\CartRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * View user's cart and calculate totals
     * GET /api/cart
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::with(['product.inventory'])->where('user_id', $userId)->get();

        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item->quantity * $item->price;
        }

        // Trả về view cart/index.blade.php
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add product to cart
     * POST /api/cart/add
     */
    public function add(CartRequest $request)
    {
        $userId = Auth::id();
        $productId = $request->product_id;
        $requestedQty = $request->quantity;

        // Business Logic: Check stock availability
        $inventory = Inventory::where('product_id', $productId)->first();
        if (!$inventory || $inventory->quantity < $requestedQty) {
            $msg = 'Sản phẩm hết hàng hoặc số lượng không đủ.';
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        // Check if product already exists in user's cart
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            // Update quantity if already exists
            $newQty = $cartItem->quantity + $requestedQty;
            
            // Re-validate stock with new total quantity
            if ($inventory->quantity < $newQty) {
                $msg = 'Tổng số lượng yêu cầu vượt quá số lượng có sẵn.';
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json(['message' => $msg], 400);
                }
                return back()->with('error', $msg);
            }
            
            $cartItem->update(['quantity' => $newQty]);
        } else {
            // Fetch product price to store in cart (snapshot price)
            $product = Product::findOrFail($productId);
            
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $requestedQty,
                'price' => $product->price
            ]);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Thêm sản phẩm thành công.',
                'cart_item' => $cartItem->fresh()
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    /**
     * Update cart item quantity
     * PUT /api/cart/update/{id}
     */
    public function update(CartRequest $request, int $id)
    {
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)->findOrFail($id);
        $requestedQty = $request->quantity;

        // Business Logic: Validate against stock
        $inventory = Inventory::where('product_id', $cartItem->product_id)->first();
        if (!$inventory || $inventory->quantity < $requestedQty) {
            $msg = 'Số lượng yêu cầu vượt quá số lượng có sẵn.';
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => $msg], 400);
            }
            return back()->with('error', $msg);
        }

        $cartItem->update(['quantity' => $requestedQty]);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'Đã cập nhật số lượng sản phẩm trong giỏ hàng.',
                'cart_item' => $cartItem->fresh()
            ]);
        }

        return back()->with('success', 'Đã cập nhật số lượng sản phẩm trong giỏ hàng.');
    }

    /**
     * Remove item from cart
     * DELETE /api/cart/remove/{id}
     */
    public function remove(int $id)
    {
        $userId = Auth::id();
        $cartItem = Cart::where('user_id', $userId)->where('id', $id)->firstOrFail();
        
        $cartItem->delete();

        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng.']);
        }

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    /**
     * Clear all items in cart (used after checkout or manual clear)
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        $userId = Auth::id();
        Cart::where('user_id', $userId)->delete();

        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json(['message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.']);
        }

        return back()->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.');
    }
}
