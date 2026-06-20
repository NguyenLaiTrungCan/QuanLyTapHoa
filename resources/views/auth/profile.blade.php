@extends('layouts.app')

@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
<div class="row justify-content-center py-4 py-lg-5">
    <div class="col-lg-9 col-xl-8">
        <div class="soft-card overflow-hidden">
            <div class="p-4 p-lg-5" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.10));">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="section-badge mb-3"><i class="bi bi-person-badge"></i> Profile</div>
                        <h1 class="page-title h3 mb-2">Hồ Sơ Cá Nhân</h1>
                        <p class="text-muted mb-0">Thông tin tài khoản hiện tại theo phong cách gọn, sáng và dễ nhìn.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-lg">Chỉnh sửa</a>
                </div>
            </div>

            <div class="p-4 p-lg-5">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 p-lg-4 soft-card h-100">
                            <div class="text-muted small mb-1">Họ tên</div>
                            <div class="fw-semibold fs-5">{{ $user->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 p-lg-4 soft-card h-100">
                            <div class="text-muted small mb-1">Email</div>
                            <div class="fw-semibold fs-5">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 p-lg-4 soft-card h-100">
                            <div class="text-muted small mb-1">Số điện thoại</div>
                            <div class="fw-semibold fs-5">{{ $user->phone ?: 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 p-lg-4 soft-card h-100">
                            <div class="text-muted small mb-1">Địa chỉ</div>
                            <div class="fw-semibold fs-5">{{ $user->address ?: 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection