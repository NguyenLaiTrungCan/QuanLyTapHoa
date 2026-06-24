@extends('layouts.app')

@section('title', 'Quản lý sản phẩm')

@section('content')
@php
    $currentSort = request('sort', 'id_desc');
    $qp = request()->except(['sort', 'page']);
    $urlIdAsc = route('admin.products.index', array_merge($qp, ['sort' => 'id_asc']));
    $urlIdDesc = route('admin.products.index', array_merge($qp, ['sort' => 'id_desc']));
    $urlPriceAsc = route('admin.products.index', array_merge($qp, ['sort' => 'price_asc']));
    $urlPriceDsc = route('admin.products.index', array_merge($qp, ['sort' => 'price_desc']));
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
    <div>
        <div class="section-badge"><i class="bi bi-box-seam"></i> Quản trị</div>
        <h1 class="page-title h2 mb-1 mt-2">Quản lý sản phẩm</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Tạo mới</a>
    </div>
</div>

<form method="GET" action="{{ route('admin.products.index') }}" class="section-card p-3 mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-semibold">Tìm theo tên sản phẩm</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Nhập tên sản phẩm">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Danh mục</label>
            <select name="category" class="form-select">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-success w-100" type="submit">Lọc</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
            <tr>
                <th>
                    <a class="text-decoration-none fw-semibold {{ $currentSort === 'id_asc' ? 'text-success' : 'text-dark' }}" href="{{ $urlIdAsc }}">ID ▲</a>
                    <a class="text-decoration-none fw-semibold {{ $currentSort === 'id_desc' ? 'text-success' : 'text-dark' }}" href="{{ $urlIdDesc }}">▼</a>
                </th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>
                    <a class="text-decoration-none fw-semibold {{ $currentSort === 'price_asc' ? 'text-success' : 'text-dark' }}" href="{{ $urlPriceAsc }}">Giá ▲</a>
                    <a class="text-decoration-none fw-semibold {{ $currentSort === 'price_desc' ? 'text-success' : 'text-dark' }}" href="{{ $urlPriceDsc }}">▼</a>
                </th>
                <th style="width: 120px;">Tồn kho</th>
                <th style="width: 220px;">Thao tác</th>
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
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary btn-sm">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm" type="submit">Xóa</button>
                            </form>
                        </div>
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

<a href="{{ route('home') }}" class="btn btn-success mt-3 px-4 py-2 rounded-pill shadow-sm">
    <i class="bi bi-house-door-fill me-2"></i>Về trang chủ
</a>
@endsection
