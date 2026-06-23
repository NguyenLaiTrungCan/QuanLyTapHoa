<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
        }

        .detail-card {
            max-width: 760px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .detail-image {
            width: 100%;
            height: 360px;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <a href="{{ route('products.index') }}" class="btn btn-link px-0">&larr; Quay lại danh sách</a>

    <div class="detail-card">
        <div>
            <div>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="detail-image" alt="{{ $product->name }}">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-secondary-subtle" style="height: 360px;">Không có ảnh</div>
                @endif
            </div>
            <div class="p-4">
                @php($stock = optional($product->inventory)->quantity ?? 0)
                <h1 class="h3">{{ $product->name }}</h1>
                <p class="text-muted mb-2">Danh mục: {{ optional($product->category)->name ?? 'Chưa có danh mục' }}</p>
                <p class="h4 text-success">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                <p class="mt-3 mb-2">{{ $product->description ?: 'Sản phẩm chưa có mô tả.' }}</p>
                <p class="mb-3">Tồn kho: <strong>{{ $stock }}</strong></p>

                @if($stock > 0)
                    <p><span class="badge text-bg-success">Còn hàng</span></p>
                @else
                    <p><span class="badge text-bg-danger">Hết hàng</span></p>
                @endif

                @if(Route::has('cart.add'))
                    <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="mb-3" style="max-width: 220px;">
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
                    <div class="mb-3" style="max-width: 220px;">
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
</body>
</html>
