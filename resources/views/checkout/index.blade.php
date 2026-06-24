@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng')

@section('content')
<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    <!-- Page Header -->
    <div class="mb-4">
        <span class="section-badge"><i class="bi bi-shield-check"></i> Thanh toán an toàn</span>
        <h2 class="page-title mt-2 fw-extrabold text-slate-800">Thông Tin Thanh Toán</h2>
        <p class="text-muted">Vui lòng kiểm tra lại thông tin đơn hàng và địa chỉ nhận hàng của bạn.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Lỗi nhập liệu:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- Left Panel: Delivery Information -->
            <div class="col-lg-7">
                <div class="card ui-card border-0 p-4 h-100">
                    <h5 class="fw-bold mb-4 text-slate-700 d-flex align-items-center">
                        <span class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center me-2" style="width: 28px; height: 28px; font-size: 0.9rem;">1</span>
                        Thông tin giao hàng
                    </h5>

                    <!-- Phone Number Input -->
                    <div class="mb-3">
                        <label for="phone" class="form-label fw-semibold text-slate-600">Số điện thoại <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" id="phone" 
                                   class="form-control bg-light border-start-0 rounded-end-3 py-2 @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', auth()->user()->phone) }}" 
                                   placeholder="Nhập số điện thoại nhận hàng..." required>
                        </div>
                        @error('phone')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Delivery Address Input -->
                    <div class="mb-3">
                        <label for="delivery_address" class="form-label fw-semibold text-slate-600">Địa chỉ nhận hàng <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3"><i class="bi bi-geo-alt"></i></span>
                            <textarea name="delivery_address" id="delivery_address" rows="3" 
                                      class="form-control bg-light border-start-0 rounded-end-3 py-2 @error('delivery_address') is-invalid @enderror" 
                                      placeholder="Nhập chi tiết số nhà, tên đường, phường/xã, quận/huyện..." required>{{ old('delivery_address', auth()->user()->address) }}</textarea>
                        </div>
                        @error('delivery_address')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order Notes Input -->
                    <div class="mb-4">
                        <label for="note" class="form-label fw-semibold text-slate-600">Ghi chú đơn hàng (Không bắt buộc)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3"><i class="bi bi-journal-text"></i></span>
                            <textarea name="note" id="note" rows="2" 
                                      class="form-control bg-light border-start-0 rounded-end-3 py-2 @error('note') is-invalid @enderror" 
                                      placeholder="Lời nhắn cho shipper, thời gian giao hàng mong muốn...">{{ old('note') }}</textarea>
                        </div>
                        @error('note')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Payment Method Section -->
                    <h5 class="fw-bold mb-3 text-slate-700 d-flex align-items-center">
                        <span class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center me-2" style="width: 28px; height: 28px; font-size: 0.9rem;">2</span>
                        Phương thức thanh toán
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="payment-card-wrapper w-100" style="cursor: pointer;">
                                <input type="radio" name="payment_method" value="COD" checked class="d-none payment-radio">
                                <div class="payment-card p-3 border rounded-4 d-flex align-items-center gap-3 bg-light transition-all active-payment">
                                    <div class="payment-icon bg-success-subtle text-success p-2 rounded-3 fs-4 d-flex">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-700">COD</div>
                                        <div class="small text-muted">Thanh toán khi nhận hàng</div>
                                    </div>
                                    <div class="ms-auto checked-indicator">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="payment-card-wrapper w-100" style="cursor: pointer;">
                                <input type="radio" name="payment_method" value="BANKING" class="d-none payment-radio">
                                <div class="payment-card p-3 border rounded-4 d-flex align-items-center gap-3 bg-light transition-all">
                                    <div class="payment-icon bg-warning-subtle text-warning p-2 rounded-3 fs-4 d-flex">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-slate-700">Chuyển khoản</div>
                                        <div class="small text-muted">Qua ứng dụng ngân hàng</div>
                                    </div>
                                    <div class="ms-auto checked-indicator d-none">
                                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel: Order Summary & Review -->
            <div class="col-lg-5">
                <div class="card ui-card border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-4 text-slate-700 d-flex align-items-center">
                        <i class="bi bi-cart3 me-2 text-success"></i>
                        Đơn hàng của bạn ({{ $cartItems->sum('quantity') }})
                    </h5>

                    <!-- Product List Scrollable -->
                    <div class="order-items-scroll mb-4" style="max-height: 240px; overflow-y: auto; padding-right: 5px;">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                                <div class="pe-3">
                                    <div class="fw-semibold text-slate-700" style="font-size: 0.95rem;">{{ $item->product->name ?? 'N/A' }}</div>
                                    <div class="small text-muted">Số lượng: <span class="fw-bold">{{ $item->quantity }}</span> × {{ number_format($item->price, 0, ',', '.') }}đ</div>
                                </div>
                                <div class="fw-bold text-slate-700">
                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Receipt summary breakdown -->
                    <div class="bg-light rounded-4 p-3 mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span class="fw-semibold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="text-success fw-semibold">Miễn phí</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold text-slate-800">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-danger">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <!-- Submit action -->
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-success btn-lg fw-bold rounded-3 py-3 d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%); border: none; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);">
                            <i class="bi bi-wallet2"></i>
                            Đặt hàng ngay
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary py-2 rounded-3">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .payment-card {
        border-color: #e2e8f0;
        transition: all 0.2s ease-in-out;
    }
    .payment-card:hover {
        border-color: #22c55e !important;
        transform: translateY(-2px);
    }
    .active-payment {
        border-color: #22c55e !important;
        background-color: #f0fdf4 !important;
        box-shadow: 0 4px 10px rgba(34, 197, 94, 0.1);
    }
    .order-items-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .order-items-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 99px;
    }
    .order-items-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 99px;
    }
    .order-items-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .fw-extrabold {
        font-weight: 800;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrappers = document.querySelectorAll('.payment-card-wrapper');
        wrappers.forEach(wrapper => {
            wrapper.addEventListener('click', function() {
                // Clear active states
                document.querySelectorAll('.payment-card').forEach(card => {
                    card.classList.remove('active-payment');
                });
                document.querySelectorAll('.checked-indicator').forEach(ind => {
                    ind.classList.add('d-none');
                });

                // Set active state for selected
                const card = this.querySelector('.payment-card');
                card.classList.add('active-payment');
                
                const indicator = this.querySelector('.checked-indicator');
                indicator.classList.remove('d-none');
            });
        });
    });
</script>
@endsection
