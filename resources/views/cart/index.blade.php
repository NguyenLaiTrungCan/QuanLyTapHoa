@extends('layouts.app')

@section('content')
<div class="container py-5" style="background-color: #F8FAFC; font-family: 'Poppins', sans-serif; color: #1E293B;">
    <h2 class="fw-bold mb-4">Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #22C55E; color: white; border: none;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($cartItems->isEmpty())
        <div class="text-center py-5 bg-white shadow-sm rounded border-0">
            <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
            <h4 class="text-muted mt-3">Giỏ hàng của bạn đang trống</h4>
            <p class="mb-4">Hãy quay lại trang danh sách để chọn mua sản phẩm nhé!</p>
            <a href="{{ route('products.index') }}" class="btn text-white px-4 py-2" style="background-color: #22C55E;">
                Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" class="ps-4">Product</th>
                                        <th scope="col">Price</th>
                                        <th scope="col" class="text-center">Qty</th>
                                        <th scope="col">Total</th>
                                        <th scope="col" class="text-center pe-4">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td class="ps-4 fw-medium">
                                                {{ $item->product->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                            
                                            <td style="width: 180px;">
                                                @php($stock = optional($item->product->inventory)->quantity ?? 0)
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex flex-column align-items-center justify-content-center gap-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" name="quantity" class="form-control form-control-sm text-center me-2" value="{{ $item->quantity }}" min="1" max="{{ $stock }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Cập nhật">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    </div>
                                                    @if($item->quantity > $stock)
                                                        <span class="text-danger small fw-bold text-center" style="font-size: 0.75rem;">Vượt quá tồn kho (Tồn: {{ $stock }})</span>
                                                    @endif
                                                </form>
                                            </td>
                                            
                                            <td class="fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                            
                                            <td class="text-center pe-4">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm text-danger border-0 bg-transparent" title="Xóa">
                                                        <i class="bi bi-trash-fill fs-5" style="color: #EF4444;"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Tóm tắt đơn hàng</h5>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tạm tính ({{ $cartItems->sum('quantity') }} sản phẩm)</span>
                            <span class="fw-medium">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-5" style="color: #EF4444;">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
 
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout.index') }}" class="btn text-white fw-bold py-2" style="background-color: #22C55E;">
                                Tiến hành thanh toán
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary py-2">
                                Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection