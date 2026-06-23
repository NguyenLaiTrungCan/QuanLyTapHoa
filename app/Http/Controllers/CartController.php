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
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

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
            return response()->json(['message' => 'Sản phẩm hết hàng hoặc số lượng không đủ.'], 400);
        }

        // Check if product already exists in user's cart
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            // Update quantity if already exists
            $newQty = $cartItem->quantity + $requestedQty;
            
            // Re-validate stock with new total quantity
            if ($inventory->quantity < $newQty) {
                return response()->json(['message' => 'Tổng số lượng yêu cầu vượt quá số lượng có sẵn.'], 400);
            }
            
            $cartItem->update(['quantity' => $newQty]);
        } else {
            // Fetch product price to store in cart (snapshot price)
            $product = Product::findOrFail($productId);
            
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $requestedQty,
                'price' => $product->price
            ]);
        }

        return back()->with('success', 'Thêm sản phẩm thành công.');
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
            return response()->json(['message' => 'Số lượng yêu cầu vượt quá số lượng có sẵn.'], 400);
        }

        $cartItem->update(['quantity' => $requestedQty]);

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

        return back()->with('success', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.');
    }
}
