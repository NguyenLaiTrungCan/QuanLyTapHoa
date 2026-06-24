@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

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

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-success mt-3 mb-4 px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách đơn hàng
        </a>
        <h1 class="page-title h2 mb-1">Đơn hàng #{{ $order->id }}</h1>
        <div class="text-muted">Đặt lúc {{ optional($order->created_at)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</div>
    </div>
    <span class="badge fs-6 {{ $statusClasses[$order->status] ?? 'text-bg-secondary' }}">
        {{ $statusLabels[$order->status] ?? $order->status }}
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="soft-card overflow-hidden mb-4">
            <div class="p-4 border-bottom">
                <h2 class="h5 fw-bold mb-0"><i class="bi bi-basket me-2 text-success"></i>Sản phẩm</h2>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">Sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th>Đơn giá</th>
                        <th class="text-end pe-4">Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ optional($item->product)->name ?? 'Sản phẩm đã xóa' }}</div>
                                @if(optional($item->product)->category)
                                    <div class="small text-muted">{{ $item->product->category->name }}</div>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td class="text-end pe-4 fw-semibold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Tổng cộng</th>
                        <th class="text-end pe-4 text-danger fs-5">{{ number_format($order->total_price, 0, ',', '.') }}đ</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="soft-card p-4 mb-4">
            <h2 class="h5 fw-bold mb-3"><i class="bi bi-truck me-2 text-success"></i>Thông tin giao hàng</h2>
            <div class="d-grid gap-3">
                <div>
                    <div class="small text-muted">Khách hàng</div>
                    <div class="fw-semibold">{{ optional($order->user)->name ?? 'Không rõ' }}</div>
                </div>
                <div>
                    <div class="small text-muted">Số điện thoại</div>
                    <div class="fw-semibold">{{ $order->phone ?: 'Chưa có' }}</div>
                </div>
                <div>
                    <div class="small text-muted">Địa chỉ nhận hàng</div>
                    <div class="fw-semibold">{{ $order->delivery_address }}</div>
                </div>
                <div>
                    <div class="small text-muted">Ghi chú</div>
                    <div>{{ $order->note ?: 'Không có ghi chú' }}</div>
                </div>
            </div>
        </div>

        <div class="soft-card p-4">
            <h2 class="h5 fw-bold mb-3"><i class="bi bi-gear me-2 text-success"></i>Thao tác</h2>

            @if($order->canBeCancelled())
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="mb-3" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle me-1"></i> Hủy đơn hàng
                    </button>
                </form>
            @else
                <div class="alert alert-light border mb-3">
                    Đơn hàng ở trạng thái này không thể hủy.
                </div>
            @endif

            @if(auth()->user()->isAdmin() && Route::has('admin.orders.updateStatus'))
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label for="status" class="form-label fw-semibold">Cập nhật trạng thái</label>
                    <select id="status" name="status" class="form-select mb-3" @disabled(in_array($order->status, ['delivered', 'canceled'], true))>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-success w-100" @disabled(in_array($order->status, ['delivered', 'canceled'], true))>
                        <i class="bi bi-check2-circle me-1"></i> Lưu trạng thái
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
