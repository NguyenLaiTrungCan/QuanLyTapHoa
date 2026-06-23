@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'delivered' => 'Đã giao',
        'canceled' => 'Đã hủy',
    ];

    $statusClasses = [
        'pending' => 'text-bg-warning',
        'processing' => 'text-bg-info',
        'shipped' => 'text-bg-primary',
        'delivered' => 'text-bg-success',
        'canceled' => 'text-bg-danger',
    ];
@endphp

<div class="soft-card overflow-hidden">
    <div class="p-4 p-lg-5 border-bottom" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.12));">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="section-badge mb-3"><i class="bi bi-receipt"></i> Order History</div>
                <h1 class="page-title h2 mb-2">{{ auth()->user()->isAdmin() ? 'Tất cả đơn hàng' : 'Đơn hàng của tôi' }}</h1>
                <p class="text-muted mb-0">Theo dõi trạng thái, xem chi tiết và hủy các đơn còn đang chờ xử lý.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-success">
                <i class="bi bi-bag-plus me-1"></i> Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th class="ps-4">Mã đơn</th>
                @if(auth()->user()->isAdmin())
                    <th>Khách hàng</th>
                @endif
                <th>Ngày đặt</th>
                <th>Số sản phẩm</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th class="text-end pe-4">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="ps-4 fw-semibold">#{{ $order->id }}</td>
                    @if(auth()->user()->isAdmin())
                        <td>{{ optional($order->user)->name ?? 'Không rõ' }}</td>
                    @endif
                    <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ (int) ($order->total_items ?? 0) }}</td>
                    <td class="fw-semibold text-success">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                    <td>
                        <span class="badge {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i> Xem
                        </a>

                        @if($order->canBeCancelled())
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle me-1"></i> Hủy
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? 7 : 6 }}" class="text-center py-5 text-muted">
                        Chưa có đơn hàng nào.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
