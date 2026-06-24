@extends('layouts.app')

@section('title', 'Thêm Danh Mục')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="soft-card overflow-hidden">
            <div class="p-4 p-lg-5" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.10));">
                <div class="section-badge mb-3"><i class="bi bi-plus-circle"></i> Category Create</div>
                <h1 class="page-title h3 mb-2">Thêm Danh Mục Mới</h1>
                <p class="text-muted mb-0">Tạo danh mục mới với bố cục sáng, rõ và đồng bộ giao diện.</p>
            </div>

            <div class="p-4 p-lg-5">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Lưu danh mục</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary btn-lg">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection