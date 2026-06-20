@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')
<section class="hero-panel p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4 position-relative">
        <div class="col-lg-7">
            <span class="soft-pill mb-3"><i class="bi bi-shop"></i> Hệ thống quản lý tạp hóa</span>
            <h1 class="display-5 fw-bold brand-heading mb-3">Mua nhanh, quản lý gọn, giao diện rõ ràng</h1>
            <p class="lead subtle mb-4">Trang chủ được dựng theo tinh thần UIUX.md: màu xanh lá chủ đạo, nền sáng, bố cục có banner, danh mục nổi bật và khu vực sản phẩm.</p>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-success btn-lg" href="{{ route('login') }}">Đăng nhập</a>
                <a class="btn btn-warning btn-lg" href="{{ route('register') }}">Đăng ký</a>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="section-card p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="fw-semibold">Banner quảng cáo</div>
                        <div class="small subtle">Khu vực nhấn mạnh khuyến mãi và sản phẩm nổi bật</div>
                    </div>
                    <span class="badge text-bg-success">New</span>
                </div>
                <div class="p-4 rounded-4" style="background: linear-gradient(135deg, rgba(34,197,94,.15), rgba(250,204,21,.24)); min-height: 220px;">
                    <div class="display-6 fw-black brand-heading">-20%</div>
                    <p class="mb-0 fw-semibold">Mì gói, đồ uống và bánh kẹo đang có chương trình ưu đãi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row g-4 mb-4">
    <div class="col-lg-3">
        <div class="section-card p-4 h-100">
            <div class="section-title mb-3">Danh mục nổi bật</div>
            <div class="d-grid gap-2">
                <a href="{{ route('login') }}" class="category-chip"><span>Đồ uống</span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('login') }}" class="category-chip"><span>Bánh kẹo</span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('login') }}" class="category-chip"><span>Gia vị</span><i class="bi bi-chevron-right"></i></a>
                <a href="{{ route('login') }}" class="category-chip"><span>Mì gói</span><i class="bi bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="section-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-title mb-1">Sản phẩm bán chạy</div>
                    <div class="subtle small">Khu vực demo theo UIUX.md cho phần product grid</div>
                </div>
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm">Xem thêm</a>
            </div>
            <div class="row g-3">
                @for ($i = 0; $i < 4; $i++)
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <div class="card-body">
                                <div class="ratio ratio-1x1 rounded-4 mb-3" style="background: linear-gradient(135deg, #eaffef, #fff6c7);"></div>
                                <div class="fw-semibold">Sản phẩm {{ $i + 1 }}</div>
                                <div class="text-success fw-bold">12.000đ</div>
                                <div class="small text-muted mb-3">Còn hàng</div>
                                <button class="btn btn-success w-100 btn-sm">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</section>

<section class="section-card p-4 mb-4">
    <div class="section-title mb-3">Sản phẩm mới</div>
    <div class="row g-3">
        @for ($i = 0; $i < 3; $i++)
            <div class="col-md-4">
                <div class="d-flex gap-3 align-items-center p-3 bg-light rounded-4">
                    <div class="rounded-4 flex-shrink-0" style="width:72px;height:72px;background:linear-gradient(135deg,#22c55e33,#facc1533);"></div>
                    <div>
                        <div class="fw-semibold">Mặt hàng mới {{ $i + 1 }}</div>
                        <div class="text-success fw-bold">18.000đ</div>
                        <div class="small subtle">Đã sẵn sàng trưng bày trên trang sản phẩm</div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</section>
@endsection
