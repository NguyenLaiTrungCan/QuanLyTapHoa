@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4 position-relative">
        <div class="col-lg-7">
            <span class="soft-pill mb-3"><i class="bi bi-shop"></i> Cửa hàng tạp hóa</span>
            <h1 class="display-5 fw-bold brand-heading mb-3">Mua nhanh, theo dõi rõ, hàng hóa luôn sẵn sàng</h1>
            <p class="lead subtle mb-4">Tìm sản phẩm, lọc theo danh mục, thêm vào giỏ và đặt hàng ngay từ dữ liệu đang có trong hệ thống.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-success btn-lg" href="{{ route('products.index') }}">
                    <i class="bi bi-bag me-1"></i> Xem sản phẩm
                </a>
                @guest
                    <a class="btn btn-warning btn-lg" href="{{ route('register') }}">Đăng ký</a>
                @else
                    <a class="btn btn-outline-success btn-lg" href="{{ route('orders.index') }}">Đơn hàng của tôi</a>
                @endguest
            </div>
        </div>
        <div class="col-lg-5">
            <div class="section-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-semibold">Sản phẩm mới trong kho</div>
                        <div class="small subtle">Các mặt hàng còn tồn kho và vừa được cập nhật</div>
                    </div>
                    <span class="badge text-bg-success">{{ $latestProducts->count() }} món</span>
                </div>
                @php($heroProduct = $latestProducts->first())
                @if($heroProduct)
                    <a href="{{ route('products.show', $heroProduct) }}" class="d-block text-decoration-none text-reset">
                        <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(34,197,94,.15), rgba(250,204,21,.24)); min-height: 220px;">
                            <div class="small text-muted mb-2">{{ optional($heroProduct->category)->name ?? 'Chưa có danh mục' }}</div>
                            <div class="display-6 fw-bold brand-heading">{{ $heroProduct->name }}</div>
                            <p class="mb-3 fw-semibold text-success">{{ number_format($heroProduct->price, 0, ',', '.') }}đ</p>
                            <span class="btn btn-success btn-sm">Xem chi tiết</span>
                        </div>
                    </a>
                @else
                    <div class="p-4 rounded-4 d-flex align-items-center" style="background: linear-gradient(135deg, rgba(34,197,94,.15), rgba(250,204,21,.24)); min-height: 220px;">
                        <div>
                            <div class="display-6 fw-bold brand-heading">Chưa có sản phẩm</div>
                            <p class="mb-0 fw-semibold">Hãy thêm sản phẩm và tồn kho để trang chủ hiển thị dữ liệu thật.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-lg-3">
        <div class="section-card p-4 h-100">
            <div class="section-title mb-3">Danh mục nổi bật</div>
            <div class="d-grid gap-2">
                @forelse($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-chip">
                        <span>{{ $category->name }}</span>
                        <span class="d-inline-flex align-items-center gap-2">
                            <span class="badge text-bg-light">{{ $category->products_count }}</span>
                            <i class="bi bi-chevron-right"></i>
                        </span>
                    </a>
                @empty
                    <div class="text-muted small">Chưa có danh mục nào.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="section-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-title mb-1">Sản phẩm bán chạy</div>
                    <div class="subtle small">Ưu tiên theo số lượng đã bán, tự dùng sản phẩm mới nếu chưa có đơn hàng</div>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-sm">Xem thêm</a>
            </div>
            <div class="row g-3">
                @forelse($bestSellingProducts as $product)
                    @php($stock = optional($product->inventory)->quantity ?? 0)
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top rounded-top-4" alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                            @else
                                <div class="ratio ratio-1x1 rounded-top-4" style="background: linear-gradient(135deg, #eaffef, #fff6c7);"></div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <div class="fw-semibold">{{ $product->name }}</div>
                                <div class="small text-muted mb-1">{{ optional($product->category)->name ?? 'Chưa có danh mục' }}</div>
                                <div class="text-success fw-bold mb-2">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                                <div class="small text-muted mb-3">Còn {{ $stock }} trong kho</div>
                                <div class="mt-auto d-grid gap-2">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">Chi tiết</a>
                                    @auth
                                        <form action="{{ route('cart.add') }}" method="POST" class="cart-add-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="return_to" value="home">
                                            <button type="submit" class="btn btn-success w-100 btn-sm" @disabled($stock <= 0)>Thêm vào giỏ</button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-success btn-sm">Đăng nhập để mua</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning mb-0">Chưa có sản phẩm còn hàng.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="section-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="section-title">Sản phẩm mới</div>
        <a href="{{ route('products.index', ['sort' => 'newest']) }}" class="btn btn-outline-success btn-sm">Tất cả sản phẩm</a>
    </div>
    <div class="row g-3">
        @forelse($latestProducts as $product)
            @php($stock = optional($product->inventory)->quantity ?? 0)
            <div class="col-md-6 col-xl-4">
                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-reset">
                    <div class="d-flex gap-3 align-items-center p-3 bg-light rounded-4 h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-4 flex-shrink-0" style="width:72px;height:72px;object-fit:cover;">
                        @else
                            <div class="rounded-4 flex-shrink-0" style="width:72px;height:72px;background:linear-gradient(135deg,#22c55e33,#facc1533);"></div>
                        @endif
                        <div class="min-w-0">
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <div class="text-success fw-bold">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                            <div class="small subtle">{{ optional($product->category)->name ?? 'Chưa có danh mục' }} · Còn {{ $stock }}</div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning mb-0">Chưa có sản phẩm mới để hiển thị.</div>
            </div>
        @endforelse
    </div>
</section>
@endsection
