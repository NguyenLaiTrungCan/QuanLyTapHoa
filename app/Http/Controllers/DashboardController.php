<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        // Get statistics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::where('is_admin', false)->count();
        $totalOrders = Order::count();

        // Get revenue statistics
        $monthlyRevenue = Order::where('status', '!=', 'canceled')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');
        
        $totalRevenue = Order::where('status', '!=', 'canceled')->sum('total_price');

        // Get revenue data for chart (last 30 days)
        $revenueChartData = $this->getRevenueChartData();

        // Get order status breakdown
        $orderStatusData = $this->getOrderStatusData();

        // Get top selling products
        $topProducts = $this->getTopSellingProducts();

        // Get low stock alerts
        $lowStockInventories = Inventory::with('product')
            ->where('quantity', '<=', 5)
            ->orderBy('quantity')
            ->limit(8)
            ->get();

        // Get recent orders (10 newest)
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'monthlyRevenue' => $monthlyRevenue,
            'totalRevenue' => $totalRevenue,
            'revenueChartData' => $revenueChartData,
            'orderStatusData' => $orderStatusData,
            'topProducts' => $topProducts,
            'lowStockInventories' => $lowStockInventories,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * Get revenue data for the last 30 days
     */
    private function getRevenueChartData()
    {
        $days = [];
        $revenues = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');

            $revenue = Order::where('status', '!=', 'canceled')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $revenues[] = $revenue;
        }

        return [
            'labels' => $days,
            'data' => $revenues,
        ];
    }

    /**
     * Get order status breakdown
     */
    private function getOrderStatusData()
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'canceled'];
        $data = [];
        $colors = [
            'pending' => '#FFC107',    // Yellow
            'processing' => '#17A2B8', // Cyan
            'shipped' => '#007BFF',    // Blue
            'delivered' => '#28A745',  // Green
            'canceled' => '#DC3545',   // Red
        ];

        foreach ($statuses as $status) {
            $count = Order::where('status', $status)->count();
            $data[] = [
                'status' => $status,
                'count' => $count,
                'color' => $colors[$status],
            ];
        }

        return $data;
    }

    /**
     * Get top selling products (top 5)
     */
    private function getTopSellingProducts()
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->with('product')
            ->get();
    }
}
