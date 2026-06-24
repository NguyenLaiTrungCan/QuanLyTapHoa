<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Chưa đăng nhập.'], 401);
            }
            return redirect()->route('login');
        }

        $ordersQuery = Order::with('user')
            ->withSum('orderItems as total_items', 'quantity')
            ->latest();

        if (! $user->isAdmin()) {
            $ordersQuery->where('user_id', $user->id);
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json(['orders' => $orders]);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Request $request, Order $order)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Chưa đăng nhập.'], 401);
            }
            return redirect()->route('login');
        }

        // Security Check: Customer cannot view other customers' orders
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bạn không có quyền xem đơn hàng này.'], 403);
            }
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['user', 'orderItems.product']);

        if ($request->expectsJson()) {
            return response()->json(['order' => $order]);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel a pending order.
     */
    public function cancel(Request $request, Order $order)
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Chưa đăng nhập.'], 401);
            }
            return redirect()->route('login');
        }

        // Security Check: Only owner or admin can cancel
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bạn không có quyền hủy đơn hàng này.'], 403);
            }
            abort(403, 'Bạn không có quyền hủy đơn hàng này.');
        }

        if (!$order->canBeCancelled()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Chỉ có thể hủy đơn hàng ở trạng thái Chờ xử lý.'], 400);
            }
            return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái Chờ xử lý.');
        }

        $order->loadMissing('orderItems');
        $cancelled = $order->cancel();

        if ($cancelled) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Hủy đơn hàng thành công.', 'order' => $order->fresh()]);
            }
            return back()->with('success', 'Đơn hàng đã được hủy thành công.');
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Không thể hủy đơn hàng lúc này.'], 400);
        }
        return back()->with('error', 'Không thể hủy đơn hàng lúc này.');
    }

    /**
     * Update order status (Admin only).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Không có quyền truy cập.'], 403);
            }
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,canceled',
        ]);

        $newStatus = $request->input('status');
        $oldStatus = $order->status;

        // Validation logic for status workflow transitions:
        // Cannot change if already delivered or cancelled
        if (in_array($oldStatus, ['delivered', 'canceled'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Không thể thay đổi trạng thái của đơn hàng đã giao hoặc đã hủy.'], 400);
            }
            return back()->with('error', 'Không thể thay đổi trạng thái của đơn hàng đã giao hoặc đã hủy.');
        }

        if ($newStatus === 'canceled') {
            // Admin cancels the order - use the model cancel method to revert stock
            $order->loadMissing('orderItems');
            $cancelled = $order->cancel();
            if (!$cancelled) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Không thể hủy đơn hàng lúc này.'], 400);
                }
                return back()->with('error', 'Không thể hủy đơn hàng lúc này.');
            }
        } else {
            // Check workflow order: pending -> processing -> shipped -> delivered
            // We restrict backward transition to pending from processing/shipped/delivered
            if ($newStatus === 'pending' && $oldStatus !== 'pending') {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Không thể chuyển trạng thái đơn hàng quay lại Chờ xử lý.'], 400);
                }
                return back()->with('error', 'Không thể chuyển trạng thái đơn hàng quay lại Chờ xử lý.');
            }

            $order->update(['status' => $newStatus]);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Cập nhật trạng thái đơn hàng thành công.', 'order' => $order->fresh()]);
        }

        return back()->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công.');
    }
}
