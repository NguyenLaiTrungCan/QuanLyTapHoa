<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->price * $item->quantity;
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process checkout and create a new order.
     */
    public function store(CheckoutRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Chưa đăng nhập.'], 401);
            }
            return redirect()->route('login');
        }

        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

        if ($cartItems->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Giỏ hàng của bạn đang trống.'], 400);
            }
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::beginTransaction();

        try {
            // Check stock and calculate total
            $total = 0;
            foreach ($cartItems as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                
                if (!$inventory || $inventory->quantity < $item->quantity) {
                    DB::rollBack();
                    $productName = $item->product ? $item->product->name : 'Sản phẩm';
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => "Sản phẩm {$productName} không đủ số lượng trong kho."
                        ], 400);
                    }
                    return back()->with('error', "Sản phẩm {$productName} không đủ số lượng trong kho.");
                }

                $total += $item->price * $item->quantity;
            }

            // Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'status' => 'pending',
                'delivery_address' => $request->input('delivery_address'),
            ]);

            // Create Order Items and update Inventory
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                $inventory = Inventory::where('product_id', $item->product_id)->first();
                $inventory->decrement('quantity', $item->quantity);
            }

            // Clear Cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Đặt hàng thành công.',
                    'order' => $order->load('orderItems')
                ], 201);
            }

            return redirect()->route('orders.show', $order->id)->with('success', 'Đơn hàng đã được đặt thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Đã xảy ra lỗi khi tạo đơn hàng.',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Đã xảy ra lỗi khi tạo đơn hàng. Vui lòng thử lại.');
        }
    }
}
