@extends('layouts.app')

@section('title', 'Cập Nhật Hồ Sơ')

@section('content')
<div class="row justify-content-center py-4 py-lg-5">
    <div class="col-lg-10">
        <div class="soft-card overflow-hidden mb-4">
            <div class="p-4 p-lg-5" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.10));">
                <div class="section-badge mb-3"><i class="bi bi-gear"></i> Update Profile</div>
                <h1 class="page-title h3 mb-2">Cập Nhật Thông Tin Cá Nhân</h1>
                <p class="text-muted mb-0">Giữ form rõ ràng, nhiều khoảng trắng và đúng bộ màu UIUX.</p>
            </div>

            <div class="p-4 p-lg-5">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Họ tên</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="phone">Số điện thoại</label>
                            <input type="text" id="phone" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="address">Địa chỉ</label>
                            <input type="text" id="address" name="address" class="form-control form-control-lg @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Lưu thay đổi</button>
                        <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-lg">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="soft-card overflow-hidden">
            <div class="p-4 p-lg-5 border-bottom" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.10));">
                <div class="section-badge mb-3"><i class="bi bi-shield-lock"></i> Password</div>
                <h2 class="page-title h4 mb-2">Đổi Mật Khẩu</h2>
                <p class="text-muted mb-0">Khu vực đổi mật khẩu theo kiểu rõ ràng, đơn giản và dễ nhập.</p>
            </div>

            <div class="p-4 p-lg-5">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                            <input type="password" id="current_password" name="current_password" class="form-control form-control-lg @error('current_password') is-invalid @enderror">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="password">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="password_confirmation">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-lg">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning btn-lg">Cập Nhật Mật Khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection