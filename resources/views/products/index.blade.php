<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
        }

        .section-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
        }

        .category-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .category-item input {
            margin-top: 0;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Sản phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Quản lý (Admin)</a>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="section-card p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label mb-1">Search</label>
                <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="Tìm tên sản phẩm...">
            </div>
            <div class="col-md-4">
                <label for="sort" class="form-label mb-1">Sắp xếp giá</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="">Mặc định</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Thấp đến cao</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Cao đến thấp</option>
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-success" type="submit">Lọc</button>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="section-card p-3">
                <h3 class="h6 mb-3">Categories</h3>

                <form method="GET" action="{{ route('products.index') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="sort" value="{{ request('sort') }}">

                    <label class="category-item">
                        <input type="radio" name="category" value="" class="form-check-input" @checked(!request('category'))>
                        <span>Tất cả</span>
                    </label>

                    @foreach($categories as $category)
                        <label class="category-item">
                            <input type="radio" name="category" value="{{ $category->id }}" class="form-check-input" @checked((string) request('category') === (string) $category->id)>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach

                    <button type="submit" class="btn btn-outline-success btn-sm mt-2">Áp dụng danh mục</button>
                </form>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="row g-3">
                @forelse($products as $product)
                    @php($stock = optional($product->inventory)->quantity ?? 0)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100 section-card">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                            @else
                                <div class="bg-secondary-subtle d-flex align-items-center justify-content-center" style="height: 220px;">Không có ảnh</div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h2 class="h6 mb-1">{{ $product->name }}</h2>
                                <p class="text-muted small mb-2">{{ optional($product->category)->name ?? 'Chưa có danh mục' }}</p>
                                <p class="fw-bold text-success mb-2">{{ number_format($product->price, 0, ',', '.') }} VND</p>

                                @if($stock > 0)
                                    <p class="badge text-bg-success mb-3 align-self-start">Còn hàng ({{ $stock }})</p>
                                @else
                                    <p class="badge text-bg-danger mb-3 align-self-start">Hết hàng</p>
                                @endif

                                <div class="mt-auto d-flex gap-2">
                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('products.show', $product) }}">Chi tiết</a>

                                    @if(Route::has('cart.add'))
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button class="btn btn-success btn-sm" type="submit" @disabled($stock <= 0)>Thêm vào giỏ</button>
                                        </form>
                                    @else
                                        <button class="btn btn-success btn-sm" type="button" disabled>Thêm vào giỏ</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">Không tìm thấy sản phẩm phù hợp.</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        </section>
    </div>
</div>
</body>
</html>
