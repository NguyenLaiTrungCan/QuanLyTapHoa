@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #F8FAFC; font-family: 'Inter', sans-serif; color: #1E293B;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Quản lý Kho hàng</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background-color: #22C55E; color: white; border: none;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Tên Sản Phẩm</th>
                            <th scope="col">Số Lượng</th>
                            <th scope="col">Vị Trí</th>
                            <th scope="col" class="text-end pe-4">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventories as $item)
                            <tr class="{{ $item->quantity < 5 ? 'table-danger' : '' }}">
                                <td class="ps-4 fw-medium">{{ $item->product->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="{{ $item->quantity < 5 ? 'text-danger fw-bold' : '' }}">
                                        {{ $item->quantity }}
                                        @if($item->quantity < 5)
                                            <i class="bi bi-exclamation-triangle-fill ms-1" style="color: #EF4444;" title="Sắp hết hàng"></i>
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $item->location ?? 'Chưa phân bổ' }}</td>
                                <td class="text-end pe-4">
                                    <a href="/inventory/{{ $item->id }}/edit" class="btn btn-sm text-white" style="background-color: #22C55E;">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Kho hàng hiện đang trống.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection