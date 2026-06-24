@extends('layouts.app')

@section('title', 'Thêm Sản Phẩm')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="soft-card overflow-hidden">
            {{-- Header Banner --}}
            <div class="p-4 p-lg-5" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.10));">
                <div class="section-badge mb-3"><i class="bi bi-plus-circle"></i> Product Create</div>
                <h1 class="page-title h3 mb-2">Thêm Sản Phẩm Mới</h1>
                <p class="text-muted mb-0">Điền đầy đủ thông tin để tạo sản phẩm mới trong hệ thống.</p>
            </div>

            <div class="p-4 p-lg-5">
                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4 rounded-3">
                        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 flex-shrink-0"></i>
                        <ul class="mb-0 ps-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Tên sản phẩm --}}
                    <div class="mb-4">
                        <label for="name" class="form-label fw-semibold">
                            <i class="bi bi-tag me-1 text-success"></i> Tên sản phẩm
                        </label>
                        <input type="text" name="name" id="name"
                               class="form-control form-control-lg @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Nhập tên sản phẩm...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Giá & Danh mục --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar me-1 text-success"></i> Giá (VND)
                            </label>
                            <input type="number" name="price" id="price" min="0"
                                   class="form-control form-control-lg @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" placeholder="0">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">
                                <i class="bi bi-grid me-1 text-success"></i> Danh mục
                            </label>
                            <select name="category_id" id="category_id"
                                    class="form-select form-select-lg @error('category_id') is-invalid @enderror">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected((string) old('category_id') === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Mô tả --}}
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">
                            <i class="bi bi-card-text me-1 text-success"></i> Mô tả
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Mô tả sản phẩm...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ảnh sản phẩm --}}
                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold">
                            <i class="bi bi-image me-1 text-success"></i> Ảnh sản phẩm
                        </label>
                        <div class="upload-area p-4 rounded-3 text-center position-relative" id="uploadArea">
                            <i class="bi bi-cloud-arrow-up fs-2 text-success mb-2 d-block"></i>
                            <p class="mb-1 fw-semibold">Kéo thả hoặc nhấn để chọn ảnh</p>
                            <p class="text-muted small mb-0">PNG, JPG, WEBP — tối đa 2 MB</p>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="position-absolute top-0 start-0 w-100 h-100 opacity-0 @error('image') is-invalid @enderror"
                                   style="cursor: pointer;" onchange="previewImage(this)">
                        </div>
                        @error('image')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                        @enderror
                        <div id="imagePreviewWrap" class="mt-3 d-none text-center">
                            <img id="imagePreview" src="" alt="Preview" class="rounded-3 border shadow-sm" style="max-height: 220px; max-width: 100%; object-fit: cover;">
                            <p class="small text-muted mt-1 mb-0" id="imageFileName"></p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-check-lg me-1"></i> Lưu sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-arrow-left me-1"></i> Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .upload-area {
        border: 2px dashed rgba(34, 197, 94, 0.4);
        background: rgba(34, 197, 94, 0.04);
        transition: border-color .25s ease, background .25s ease;
        cursor: pointer;
    }
    .upload-area:hover,
    .upload-area:focus-within {
        border-color: #22c55e;
        background: rgba(34, 197, 94, 0.09);
    }
    .form-control, .form-select {
        border-radius: .75rem;
        border-color: var(--brand-border, #dbe4ee);
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 .2rem rgba(34,197,94,.18);
    }
    .btn-success {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        transition: opacity .2s ease, transform .15s ease;
    }
    .btn-success:hover { opacity: .9; transform: translateY(-1px); }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(input) {
        const wrap = document.getElementById('imagePreviewWrap');
        const img  = document.getElementById('imagePreview');
        const name = document.getElementById('imageFileName');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                name.textContent = input.files[0].name;
                wrap.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
