@extends('layouts.app')

@section('title', 'Quản Lý Danh Mục')

@section('content')
<div class="soft-card overflow-hidden">
    <div class="p-4 p-lg-5 border-bottom" style="background: linear-gradient(135deg, rgba(34,197,94,.12), rgba(250,204,21,.12));">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="section-badge mb-3"><i class="bi bi-tags"></i> Quản Lý Danh Mục</div>
                <h1 class="page-title h2 mb-2">Danh Mục</h1>            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-lg">
                <i class="bi bi-plus-lg me-1"></i> Thêm danh mục
            </a>
        </div>
    </div>

    <div class="p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Số sản phẩm</th>
                    <th class="text-end pe-4">Thao tác</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td class="ps-4">{{ $category->id }}</td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td>{{ $category->description ?: 'Chưa có mô tả' }}</td>
                        <td><span class="badge text-bg-success rounded-pill">{{ $category->products_count }}</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Chưa có danh mục nào.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection