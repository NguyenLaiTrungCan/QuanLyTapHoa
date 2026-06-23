<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sort-btn {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            font-weight: 600;
            color: inherit;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .sort-btn:hover { color: #0d6efd; }
        .sort-btn .arrow { font-size: 0.75rem; color: #adb5bd; }
        .sort-btn .arrow.active { color: #0d6efd; }
    </style>
</head>
<body class="bg-light">
@php
    $currentSort = request('sort', 'id_desc');
    $qp = request()->except('sort');
    $urlIdAsc    = url()->current() . '?' . http_build_query(array_merge($qp, ['sort' => 'id_asc']));
    $urlIdDesc   = url()->current() . '?' . http_build_query(array_merge($qp, ['sort' => 'id_desc']));
    $urlPriceAsc = url()->current() . '?' . http_build_query(array_merge($qp, ['sort' => 'price_asc']));
    $urlPriceDsc = url()->current() . '?' . http_build_query(array_merge($qp, ['sort' => 'price_desc']));
@endphp
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Quản lý sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Tạo mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                <tr>
                    {{-- Cột ID với nút sort --}}
                    <th>
                        <button class="sort-btn" onclick="window.location='{{ $urlIdAsc }}'">
                            ID
                            <span class="arrow {{ $currentSort === 'id_asc' ? 'active' : '' }}">▲</span>
                        </button>
                        <button class="sort-btn" onclick="window.location='{{ $urlIdDesc }}'">
                            <span class="arrow {{ $currentSort === 'id_desc' ? 'active' : '' }}">▼</span>
                        </button>
                    </th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    {{-- Cột Giá với nút sort --}}
                    <th>
                        <button class="sort-btn" onclick="window.location='{{ $urlPriceAsc }}'">
                            Giá
                            <span class="arrow {{ $currentSort === 'price_asc' ? 'active' : '' }}">▲</span>
                        </button>
                        <button class="sort-btn" onclick="window.location='{{ $urlPriceDsc }}'">
                            <span class="arrow {{ $currentSort === 'price_desc' ? 'active' : '' }}">▼</span>
                        </button>
                    </th>
                    <th>Tồn kho</th>
                    <th style="width: 180px;">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ optional($product->category)->name }}</td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                        <td>{{ optional($product->inventory)->quantity ?? 0 }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">Chưa có sản phẩm nào.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-link px-0 mt-2">&larr; Về trang khách hàng</a>
</div>
</body>
</html>
