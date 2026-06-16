@extends('layouts.app')

@section('title', 'Đăng Ký')

@section('content')
<div class="row justify-content-center py-4 py-lg-5">
    <div class="col-lg-11 col-xl-10">
        <div class="row g-0 overflow-hidden soft-card">
            <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center p-5" style="background: linear-gradient(160deg, rgba(250,204,21,.18), rgba(34,197,94,.16));">
                <div class="text-center px-3">
                    <div class="section-badge mb-3"><i class="bi bi-shop-window"></i> Create Account</div>
                    <h2 class="page-title fw-bold mb-3">Tạo tài khoản để theo dõi giỏ hàng và đơn hàng.</h2>
                    <p class="text-muted mb-0">UI bám theo file thiết kế: bố cục gọn, màu xanh lá - vàng, ưu tiên form rõ ràng và dễ nhập.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 bg-transparent h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="section-badge mb-3"><i class="bi bi-person-plus"></i> Đăng ký</div>
                            <h1 class="page-title h3 mb-0">Đăng Ký Tài Khoản</h1>
                        </div>

                        <form action="{{ route('register.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="name">Họ tên</label>
                                    <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nguyễn Văn A">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@gmail.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="phone">Số điện thoại</label>
                                    <input type="text" id="phone" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0901234567">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="address">Địa chỉ</label>
                                    <input type="text" id="address" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror" value="{{ old('address') }}" placeholder="TP. Hồ Chí Minh">
                                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="••••••••">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg" placeholder="••••••••">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-lg w-100 mt-4">Đăng ký</button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Đã có tài khoản?
                            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Đăng nhập</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection