@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    @vite('resources/css/dashboard.css')
@endpush

@section('content')
<div class="dashboard-container py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start gap-3">
        <div>
            <span class="text-uppercase tracking-wider text-muted fw-bold small">Trang Quản Trị</span>
            <h1 class="header-welcome mb-1">Xin chào {{ auth()->user()->name }} 👋</h1>
            <p class="header-subtitle mb-0">Theo dõi tình hình kinh doanh hôm nay</p>
        </div>
        <div class="header-actions">
            <div class="notification-badge" title="Thông báo">
                <i class="bi bi-bell fs-5"></i>
                @if($lowStockInventories->isNotEmpty())
                    <span class="badge-dot"></span>
                @endif
            </div>
            <a href="{{ route('profile') }}" class="admin-profile-chip">
                <div class="admin-avatar">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span>Hồ sơ Admin</span>
            </a>
        </div>
    </div>

    <!-- KPI Cards Row -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Tổng Sản Phẩm -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body-clean kpi-card">
                    <div class="kpi-icon-wrapper kpi-blue">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="kpi-label">Tổng Sản Phẩm</div>
                    <div class="kpi-value">{{ $totalProducts }}</div>
                    <div class="kpi-trend trend-neutral">
                        <i class="bi bi-plus-circle-fill"></i> +12 sản phẩm mới
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Tổng Danh Mục -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body-clean kpi-card">
                    <div class="kpi-icon-wrapper kpi-green">
                        <i class="bi bi-tags"></i>
                    </div>
                    <div class="kpi-label">Tổng Danh Mục</div>
                    <div class="kpi-value">{{ $totalCategories }}</div>
                    <div class="kpi-trend trend-neutral">
                        <i class="bi bi-plus-circle-fill"></i> +1 danh mục mới
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Tổng Người Dùng -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body-clean kpi-card">
                    <div class="kpi-icon-wrapper kpi-purple">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="kpi-label">Tổng Người Dùng</div>
                    <div class="kpi-value">{{ $totalUsers }}</div>
                    <div class="kpi-trend trend-neutral">
                        <i class="bi bi-person-plus-fill"></i> +15 người dùng mới
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Tổng Đơn Hàng -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card dashboard-card h-100">
                <div class="card-body-clean kpi-card">
                    <div class="kpi-icon-wrapper kpi-orange">
                        <i class="bi bi-cart-check"></i>
                    </div>
                    <div class="kpi-label">Tổng Đơn Hàng</div>
                    <div class="kpi-value">{{ $totalOrders }}</div>
                    <div class="kpi-trend trend-up">
                        <i class="bi bi-graph-up-arrow"></i> +18 hôm nay
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Doanh Thu Tháng -->
        <div class="col-12 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body-clean d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Doanh Thu Tháng</div>
                        <div class="revenue-card-value text-success">{{ number_format($monthlyRevenue, 0, ',', '.') }} ₫</div>
                        <div class="revenue-trend text-success">
                            <i class="bi bi-arrow-up-right-circle-fill"></i> +15% so với tháng trước
                        </div>
                    </div>
                    <div class="fs-1 text-success opacity-25">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng Doanh Thu -->
        <div class="col-12 col-md-6">
            <div class="card dashboard-card">
                <div class="card-body-clean d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Tổng Doanh Thu</div>
                        <div class="revenue-card-value text-primary">{{ number_format($totalRevenue, 0, ',', '.') }} ₫</div>
                        <div class="revenue-trend text-primary">
                            <i class="bi bi-arrow-up-right-circle-fill"></i> +22% năm nay
                        </div>
                    </div>
                    <div class="fs-1 text-primary opacity-25">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Cảnh Báo Tồn Kho Thấp</h5>
                    <a href="{{ route('admin.inventory.index') }}" class="order-action-link">Quản lý kho</a>
                </div>
                <div class="card-body-clean">
                    @if($lowStockInventories->isEmpty())
                        <div class="text-center text-muted py-3">Không có sản phẩm nào sắp hết hàng.</div>
                    @else
                        <div class="row g-3">
                            @foreach($lowStockInventories as $inventory)
                                <div class="col-12 col-md-6 col-xl-3">
                                    <div class="low-stock-item">
                                        <div>
                                            <div class="fw-semibold">{{ optional($inventory->product)->name ?? 'N/A' }}</div>
                                            <div class="small text-muted">{{ $inventory->location ?: 'Chưa có vị trí kho' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="low-stock-count">{{ $inventory->quantity }}</div>
                                            <a href="{{ route('admin.inventory.edit', $inventory) }}" class="small text-decoration-none">Cập nhật</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-12 col-lg-8">
            <div class="card dashboard-card h-100">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Biểu Đồ Doanh Thu</h5>
                    <div class="chart-filter-group">
                        <button class="btn-chart-filter" data-range="7">7 ngày</button>
                        <button class="btn-chart-filter active" data-range="30">30 ngày</button>
                        <button class="btn-chart-filter" data-range="90">90 ngày</button>
                        <button class="btn-chart-filter" data-range="365">1 năm</button>
                    </div>
                </div>
                <div class="card-body-clean">
                    <div style="height: 320px; position: relative;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-12 col-lg-4">
            <div class="card dashboard-card h-100">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-pie-chart me-2 text-primary"></i>Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body-clean d-flex flex-column justify-content-center">
                    <div style="height: 250px; position: relative;" class="mb-3">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products and Status Detail Row -->
    <div class="row g-4 mb-4">
        <!-- Top Products -->
        <div class="col-12 col-lg-6">
            <div class="card dashboard-card h-100">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-trophy me-2 text-warning"></i>Top Sản Phẩm Bán Chạy</h5>
                </div>
                <div class="card-body-clean">
                    <div class="top-product-list">
                        @php
                            $maxQty = count($topProducts) > 0 ? max($topProducts->pluck('total_quantity')->toArray()) : 1;
                        @endphp
                        @forelse($topProducts as $index => $item)
                            @php
                                $rankClass = 'rank-other';
                                $rankIcon = ($index + 1);
                                if ($index == 0) { $rankClass = 'rank-1'; $rankIcon = '🥇'; }
                                elseif ($index == 1) { $rankClass = 'rank-2'; $rankIcon = '🥈'; }
                                elseif ($index == 2) { $rankClass = 'rank-3'; $rankIcon = '🥉'; }
                                
                                $prodName = $item->product->name ?? 'N/A';
                                $initial = substr($prodName, 0, 1);
                                $qty = $item->total_quantity;
                                $percent = ($qty / max(1, $maxQty)) * 100;
                            @endphp
                            <div class="top-product-item">
                                <div class="product-rank {{ $rankClass }}">
                                    {{ $rankIcon }}
                                </div>
                                <div class="product-avatar">
                                    @if(!empty($item->product->image))
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $prodName }}">
                                    @else
                                        {{ $initial }}
                                    @endif
                                </div>
                                <div class="product-info-wrapper">
                                    <div class="product-title">{{ $prodName }}</div>
                                    <div class="product-sales-bar-container">
                                        <div class="product-sales-bar">
                                            <div class="product-sales-fill" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <div class="product-sales-count">{{ $qty }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">Không có dữ liệu sản phẩm bán chạy</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Details -->
        <div class="col-12 col-lg-6">
            <div class="card dashboard-card h-100">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-list-stars me-2 text-primary"></i>Chi Tiết Trạng Thái</h5>
                </div>
                <div class="card-body-clean">
                    <div class="status-widget-container">
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ Xử Lý',
                                'processing' => 'Đang Xử Lý',
                                'shipped' => 'Đang Giao',
                                'delivered' => 'Đã Giao',
                                'canceled' => 'Đã Hủy'
                            ];
                            $statusBadges = [
                                'pending' => 'bg-pending',
                                'processing' => 'bg-processing',
                                'shipped' => 'bg-shipping',
                                'delivered' => 'bg-delivered',
                                'canceled' => 'bg-canceled'
                            ];
                        @endphp
                        @foreach($orderStatusData as $status)
                            @php
                                $label = $statusLabels[$status['status']] ?? $status['status'];
                                $count = $status['count'];
                                $percent = ($count / max(1, $totalOrders)) * 100;
                                $badgeClass = $statusBadges[$status['status']] ?? 'bg-secondary';
                            @endphp
                            <div class="status-widget-item">
                                <div class="status-widget-info">
                                    <span class="status-badge {{ $badgeClass }}">{{ $label }} ({{ $count }})</span>
                                    <span class="status-widget-count">{{ round($percent) }}%</span>
                                </div>
                                <div class="status-widget-progress">
                                    <div class="status-widget-bar bar-{{ $status['status'] }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Row -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header-clean">
                    <h5 class="dashboard-card-title mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Đơn Hàng Gần Đây</h5>
                </div>
                <div class="card-body-clean p-0">
                    <div class="table-responsive">
                        <table class="table-clean">
                            <thead>
                                <tr>
                                    <th>Mã Đơn</th>
                                    <th>Khách Hàng</th>
                                    <th>Tổng Tiền</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Đặt</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    @php
                                        $statusBadges = [
                                            'pending' => 'bg-pending',
                                            'processing' => 'bg-processing',
                                            'shipped' => 'bg-shipping',
                                            'delivered' => 'bg-delivered',
                                            'canceled' => 'bg-canceled',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ Xử Lý',
                                            'processing' => 'Đang Xử Lý',
                                            'shipped' => 'Đang Giao',
                                            'delivered' => 'Đã Giao',
                                            'canceled' => 'Đã Hủy',
                                        ];
                                    @endphp
                                    <tr>
                                        <td>
                                            <span class="order-code">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <span class="order-customer">{{ $order->user->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="order-price">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span>
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $statusBadges[$order->status] ?? 'bg-secondary' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('orders.show', $order->id) }}" class="order-action-link">
                                                Chi Tiết
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Không có đơn hàng nào gần đây</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="application/json" id="dashboardData">
{
    "revenue": {
        "labels": {!! json_encode($revenueChartData['labels']) !!},
        "data": {!! json_encode($revenueChartData['data']) !!}
    },
    "status": {!! json_encode($orderStatusData) !!}
}
</script>
<script>
    // Load chart data from JSON
    const jsonData = JSON.parse(document.getElementById('dashboardData').textContent);
    window.dashboardData = jsonData;
</script>
@vite('resources/js/dashboard-charts.js')
@endpush
@endsection
