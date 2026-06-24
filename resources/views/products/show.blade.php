@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="d-flex flex-column gap-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <span class="section-badge"><i class="bi bi-box-seam me-2"></i>Chi tiết sản phẩm</span>
            <h2 class="page-title h3 mt-2 mb-0">{{ $product->name }}</h2>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="hero-panel overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 420px; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-secondary-subtle" style="height: 420px;">Không có ảnh</div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="ui-card p-4 h-100">
                @php($stock = optional($product->inventory)->quantity ?? 0)
                <p class="text-muted mb-2">Danh mục: {{ optional($product->category)->name ?? 'Chưa có danh mục' }}</p>
                <p class="h3 fw-bold text-success mb-3">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                <p class="mb-3">{{ $product->description ?: 'Sản phẩm chưa có mô tả.' }}</p>
                <p class="mb-3">Tồn kho: <strong>{{ $stock }}</strong></p>

                @if($stock > 0)
                    <p class="mb-4"><span class="badge text-bg-success">Còn hàng</span></p>
                @else
                    <p class="mb-4"><span class="badge text-bg-danger">Hết hàng</span></p>
                @endif

                @if(Route::has('cart.add'))
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="return_to" value="products.show">
                        <div class="mb-3" style="max-width: 240px;">
                            <label for="qty" class="form-label mb-1">Số lượng</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeQty(-1)">-</button>
                                <input id="qty" name="quantity" type="number" class="form-control text-center" value="1" min="1" max="{{ max(1, $stock) }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="changeQty(1)">+</button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success" @disabled($stock <= 0)>Thêm vào giỏ</button>
                    </form>
                @else
                    <div class="mb-3" style="max-width: 240px;">
                        <label for="qty-disabled" class="form-label mb-1">Số lượng</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary" disabled>-</button>
                            <input id="qty-disabled" type="number" class="form-control text-center" value="1" min="1" disabled>
                            <button type="button" class="btn btn-outline-secondary" disabled>+</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" disabled>Thêm vào giỏ</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function changeQty(step) {
        const input = document.getElementById('qty');
        if (!input) {
            return;
        }

        const min = parseInt(input.min || '1', 10);
        const max = parseInt(input.max || '999999', 10);
        const current = parseInt(input.value || '1', 10);
        let next = current + step;

        if (Number.isNaN(next)) {
            next = 1;
        }

        next = Math.max(min, Math.min(max, next));
        input.value = next;
    }
</script>
@endpush
