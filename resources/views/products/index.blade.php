@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')

@section('content')
<div class="d-flex flex-column gap-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
        <div>
            <span class="section-badge"><i class="bi bi-box-seam me-2"></i>Sản phẩm</span>
            <h2 class="page-title h3 mt-2 mb-0">Khám phá sản phẩm</h2>
        </div>
    </div>

    <form method="GET" action="{{ route('products.index') }}" class="section-card p-3 mb-0" id="filterForm">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="search" class="form-label mb-1">Tìm kiếm</label>
                <div class="d-flex gap-2">
                    <input id="search" name="search" type="text" class="form-control" value="{{ request('search') }}" placeholder="Tìm tên sản phẩm...">
                    <button class="btn btn-success" type="submit">Tìm</button>
                </div>
            </div>
            <div class="col-md-4">
                <label for="sort" class="form-label mb-1">Sắp xếp giá</label>
                <select id="sort" name="sort" class="form-select">
                    <option value="">Mặc định</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Thấp đến cao</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Cao đến thấp</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">&nbsp;</label>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">Đặt lại</a>
            </div>
        </div>
    </form>

    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="section-card p-3">
                <h3 class="h6 mb-3">Danh mục</h3>

                <form method="GET" action="{{ route('products.index') }}" id="categoryForm">
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
                                        <form action="{{ route('cart.add') }}" method="POST" class="cart-add-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="return_to" value="products.index">
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
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </section>
    </div>
</div>
@endsection

@push('styles')
<style>
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
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('filterForm');
        const categoryForm = document.getElementById('categoryForm');

        if (filterForm) {
            filterForm.querySelector('#sort').addEventListener('change', function () {
                filterForm.submit();
            });
        }

        if (categoryForm) {
            categoryForm.querySelectorAll('input[name="category"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    categoryForm.submit();
                });
            });
        }
    });
</script>
@endpush
