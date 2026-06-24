@extends('layouts.app')

@section('title', 'Đăng Nhập')

@section('content')
<div class="row justify-content-center py-4 py-lg-5">
    <div class="col-lg-10 col-xl-9">
        <div class="row g-0 overflow-hidden soft-card">
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center p-5" style="background: linear-gradient(160deg, rgba(34,197,94,.14), rgba(250,204,21,.14));">
                <div class="text-center px-3">
                    <div class="section-badge mb-3"><i class="bi bi-person-check"></i> Customer Portal</div>
                    <h2 class="page-title fw-bold mb-3">Đăng nhập để mua sắm và quản lý đơn hàng.</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 bg-transparent h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <div class="section-badge mb-3"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</div>
                            <h1 class="page-title h3 mb-0">Đăng Nhập</h1>
                        </div>

                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@gmail.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="password">Mật khẩu</label>
                                <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="••••••••">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100">Đăng nhập</button>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Chưa có tài khoản?
                            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Đăng ký</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection